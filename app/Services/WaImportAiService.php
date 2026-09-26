<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WaImportDraft;
use App\Models\WorkerProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class WaImportAiService
{
    private string $groqEndpoint = 'https://api.groq.com/openai/v1/chat/completions';

    public function process(WaImportDraft $draft): void
    {
        try {
            if (!Storage::disk('local')->exists($draft->chat_file_path)) {
                throw new Exception("File chat tidak ditemukan di storage.");
            }
            
            $chatContent = Storage::disk('local')->get($draft->chat_file_path);
            if (mb_strlen($chatContent) > 12000) {
                $chatContent = mb_substr($chatContent, 0, 6000)
                    . "\n\n[Bagian tengah chat dipotong karena batas token AI]\n\n"
                    . mb_substr($chatContent, -6000);
            }
            
            // Get workers + skills for prompt
            $workers = WorkerProfile::with(['user', 'skills'])
                ->where('tefa_unit_id', $draft->tefa_unit_id)
                ->get()
                ->map(function($w) {
                    $skills = $w->skills->map(function($s) {
                        return $s->name . ' (' . ucfirst($s->pivot->proficiency_level) . ')';
                    })->join(', ');
                    return "- ID: {$w->id} | Nama: {$w->user->name} | Skills & Kemahiran: {$skills}";
                })
                ->join("\n");

            $prompt = $this->buildGroqPrompt($chatContent, $workers);
            $aiJsonText = $this->callGroq($prompt);
            $aiJsonText = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($aiJsonText)) ?? $aiJsonText;
            
            $extractedData = json_decode($aiJsonText, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Gagal parse response JSON dari AI: " . json_last_error_msg());
            }

            $draft->update([
                'status' => 'ready',
                'ai_result' => $extractedData,
            ]);

        } catch (Exception $e) {
            $draft->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function callGroq(string $prompt): string
    {
        $apiKey = trim((string) env('GROQ_API_KEY'));

        if (empty($apiKey)) {
            throw new Exception('GROQ_API_KEY tidak ditemukan pada file .env');
        }

        $http = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30);

        if (app()->environment('local')) {
            $http->withoutVerifying();
        }

        $modelsResponse = $http->get('https://api.groq.com/openai/v1/models');

        if ($modelsResponse->failed()) {
            Log::error('Gagal mengambil daftar model Groq: ' . $modelsResponse->body());
            throw new Exception('GROQ API Key tidak valid / terblokir.');
        }

        $activeModels = collect($modelsResponse->json('data', []))
            ->pluck('id')
            ->filter(fn($id) => is_string($id)
                && !str_contains($id, 'whisper')
                && !str_contains($id, 'safetensors')
                && !str_contains($id, 'guard')
                && !str_contains($id, 'orpheus')
                && !str_contains($id, 'allam'))
            ->values();

        if ($activeModels->isEmpty()) {
            throw new Exception('Tidak ada model text chat yang aktif di akun Groq ini.');
        }

        $lastError = 'respons AI kosong';
        foreach ($activeModels as $model) {
            $response = $http->post($this->groqEndpoint, [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 2048,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? '{}';
            }

            $lastError = $response->json('error.message') ?? $response->body();
            Log::warning("Model Groq {$model} gagal: " . $lastError);
        }

        throw new Exception('Groq gagal memproses request: ' . $lastError);
    }

    private function buildGroqPrompt(string $chatText, string $workersText): string
    {
        return <<<PROMPT
Anda adalah AI asisten untuk "TEFA Management Platform". 
Tugas Anda mengekstrak informasi proyek dari percakapan WhatsApp antara pihak sekolah (Admin) dan Klien, lalu membaginya menjadi tugas-tugas untuk siswa berdasarkan skill mereka.

Instruksi:
1. Temukan nama klien (dan kontaknya jika ada).
2. Temukan judul proyek secara keseluruhan.
3. Buat ringkasan pesanan/proyek (summary).
4. Temukan harga akhir yang benar-benar disepakati (dalam bentuk angka bulat). Jika belum ada kesepakatan, kembalikan null.
5. Temukan deadline yang disepakati. Kembalikan dalam format YYYY-MM-DD jika jelas, jika belum ada kesepakatan kembalikan null. Jangan membuat tanggal berdasarkan perkiraan.
6. Pecah pekerjaan tersebut menjadi beberapa sub-tugas (tasks) yang bisa dikerjakan oleh siswa.
7. Untuk tiap sub-tugas, berikan target/goals yang jelas.
8. Delegasi Tugas: Berdasarkan Daftar Siswa di bawah ini, rekomendasikan siapa yang paling cocok menjadi Ketua Tim (leader_id) dan siapa Anggota Pendukung (member_ids) berdasarkan kecocokan skill mereka dengan tugas tersebut. **PENTING**: Perhatikan tingkat kemahiran (Beginner, Intermediate, Advanced). Prioritaskan siswa dengan kemahiran lebih tinggi (Advanced/Intermediate) sebagai Ketua Tim. Jika tidak ada yang cocok, biarkan null/kosong.

DAFTAR SISWA TERSEDIA:
{$workersText}

Keluarkan jawaban murni dalam format JSON sesuai skema berikut tanpa backticks atau teks pengantar:
{
  "client_name": "string",
  "client_contact": "string",
  "project_title": "string",
  "project_summary": "string",
    "agreed_price": integer|null,
    "agreed_deadline": "YYYY-MM-DD"|null,
  "tasks": [
    {
      "title": "string",
      "instructions": "string",
      "goals": "string",
      "reasoning": "string",
      "leader_id": "string|null",
      "member_ids": ["string"]
    }
  ]
}

Teks Chat:
{$chatText}
PROMPT;
    }
}

