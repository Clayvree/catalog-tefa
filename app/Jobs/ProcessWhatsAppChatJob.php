<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessWhatsAppChatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $filePath,
        public readonly string $tefaUnitId,
        public readonly string $uploaderId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // 1. Baca isi file
            if (!Storage::disk('local')->exists($this->filePath)) {
                throw new \Exception("File chat tidak ditemukan di storage.");
            }
            
            $chatContent = Storage::disk('local')->get($this->filePath);

            // 2. Siapkan Prompt untuk Gemini API
            $prompt = $this->buildGeminiPrompt($chatContent);

            // 3. Panggil Gemini API
            $geminiApiKey = env('GEMINI_API_KEY');
            if (!$geminiApiKey) {
                throw new \Exception("GEMINI_API_KEY belum di-set di .env");
            }

            $response = Http::withHeaders([
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
                throw new \Exception('Gemini API Error: ' . $response->body());
            }

            // 4. Parse JSON Response
            $responseData = $response->json();
            $aiJsonText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            $extractedData = json_decode($aiJsonText, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Gagal parse response JSON dari AI");
            }

            // 5. Simpan draft ke database / broadcast ke frontend via pusher/reverb
            // Untuk sementara kita log, implementasi realnya akan simpan ke tabel temporary 
            // agar Admin bisa review (Interactive Confirm/Override modal) sebelum jadi Project betulan.
            
            Log::info("AI Chat Extraction Success untuk file: {$this->filePath}", $extractedData);

        } catch (\Exception $e) {
            Log::error("Job ProcessWhatsAppChatJob Gagal: " . $e->getMessage());
            $this->fail($e);
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
4. Temukan harga akhir yang disepakati (dalam bentuk angka bulat).
5. Pecah pekerjaan tersebut menjadi beberapa sub-tugas (tasks) yang bisa dikerjakan oleh siswa.
6. Untuk tiap sub-tugas, berikan alasan (reasoning) skill apa yang dibutuhkan (misal: "Membutuhkan skill editing video").

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
