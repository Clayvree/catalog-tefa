<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WaImportDraft;
use App\Models\WorkerProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Exception;

class WaImportAiService
{
    public function process(WaImportDraft $draft): void
    {
        try {
            if (!Storage::disk('local')->exists($draft->chat_file_path)) {
                throw new Exception("File chat tidak ditemukan di storage.");
            }
            
            $chatContent = Storage::disk('local')->get($draft->chat_file_path);
            
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

            $prompt = $this->buildGeminiPrompt($chatContent, $workers);

            $geminiApiKey = env('GEMINI_API_KEY');
            if (!$geminiApiKey) {
                throw new Exception("GEMINI_API_KEY belum di-set di .env");
            }

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiApiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                throw new Exception('Gemini API Error: ' . $response->body());
            }

            $responseData = $response->json();
            $aiJsonText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
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

    private function buildGeminiPrompt(string $chatText, string $workersText): string
    {
        return <<<PROMPT
Anda adalah AI asisten untuk "TEFA Management Platform". 
Tugas Anda mengekstrak informasi proyek dari percakapan WhatsApp antara pihak sekolah (Admin) dan Klien, lalu membaginya menjadi tugas-tugas untuk siswa berdasarkan skill mereka.

Instruksi:
1. Temukan nama klien (dan kontaknya jika ada).
2. Temukan judul proyek secara keseluruhan.
3. Buat ringkasan pesanan/proyek (summary).
4. Temukan harga akhir yang disepakati (dalam bentuk angka bulat). Jika tidak ada, kembalikan null atau 0.
5. Pecah pekerjaan tersebut menjadi beberapa sub-tugas (tasks) yang bisa dikerjakan oleh siswa.
6. Untuk tiap sub-tugas, berikan target/goals yang jelas.
7. Delegasi Tugas: Berdasarkan Daftar Siswa di bawah ini, rekomendasikan siapa yang paling cocok menjadi Ketua Tim (leader_id) dan siapa Anggota Pendukung (member_ids) berdasarkan kecocokan skill mereka dengan tugas tersebut. **PENTING**: Perhatikan tingkat kemahiran (Beginner, Intermediate, Advanced). Prioritaskan siswa dengan kemahiran lebih tinggi (Advanced/Intermediate) sebagai Ketua Tim. Jika tidak ada yang cocok, biarkan null/kosong.

DAFTAR SISWA TERSEDIA:
{$workersText}

Keluarkan jawaban murni dalam format JSON sesuai skema berikut tanpa backticks atau teks pengantar:
{
  "client_name": "string",
  "client_contact": "string",
  "project_title": "string",
  "project_summary": "string",
  "agreed_price": integer,
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

