<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WaImportDraft;
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

            $prompt = $this->buildGeminiPrompt($chatContent);

            $geminiApiKey = env('GEMINI_API_KEY');
            if (!$geminiApiKey) {
                throw new Exception("GEMINI_API_KEY belum di-set di .env");
            }

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$geminiApiKey}", [
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

    private function buildGeminiPrompt(string $chatText): string
    {
        return <<<PROMPT
Anda adalah AI asisten untuk "TEFA Management Platform". 
Tugas Anda mengekstrak informasi proyek dari percakapan WhatsApp antara pihak sekolah (Admin) dan Klien.

Instruksi:
1. Temukan nama klien (dan kontaknya jika ada).
2. Temukan judul proyek secara keseluruhan.
3. Buat ringkasan pesanan/proyek (summary).
4. Temukan harga akhir yang disepakati (dalam bentuk angka bulat). Jika tidak ada, kembalikan null atau 0.
5. Pecah pekerjaan tersebut menjadi beberapa sub-tugas (tasks) yang bisa dikerjakan oleh siswa.
6. Untuk tiap sub-tugas, berikan alasan (reasoning) skill apa yang dibutuhkan.

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
      "reasoning": "string"
    }
  ]
}

Teks Chat:
{$chatText}
PROMPT;
    }
}
