<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

$chatPath = public_path('contoh_chat_wa_tefa.txt');
if (!file_exists($chatPath)) {
    die("Chat file not found: $chatPath\n");
}
$chatContent = file_get_contents($chatPath);

// Limit length for token safety
if (mb_strlen($chatContent) > 12000) {
    $chatContent = mb_substr($chatContent, 0, 6000) . "\n\n[Bagian tengah chat dipotong]\n\n" . mb_substr($chatContent, -6000);
}

$prompt = <<<PROMPT
Anda adalah asisten AI khusus TEFA (sekolah). Tugas Anda hanya menjawab pertanyaan yang berkaitan dengan konteks TEFA yang terdapat dalam percakapan berikut. Jangan menambahkan informasi di luar percakapan atau konteks pendidikan.

Teks Chat:
{$chatContent}

Pertanyaan: Berikan ringkasan proyek, nama klien, kontak, dan harga yang disepakati.
PROMPT;

$apiKey = trim((string) env('GROQ_API_KEY'));
if (empty($apiKey)) {
    die("GROQ_API_KEY tidak ditemukan di .env\n");
}

$http = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json',
])->timeout(30);

if (app()->environment('local')) {
    $http->withoutVerifying();
}

// Get active models (same logic as WaImportAiService)
$modelsResp = $http->get('https://api.groq.com/openai/v1/models');
if ($modelsResp->failed()) {
    die("Gagal dapatkan model list: " . $modelsResp->body() . "\n");
}
$activeModels = collect($modelsResp->json('data', []))
    ->pluck('id')
    ->filter(fn($id) => is_string($id) && !str_contains($id, 'whisper') && !str_contains($id, 'safetensors') && !str_contains($id, 'guard') && !str_contains($id, 'orpheus') && !str_contains($id, 'allam'))
    ->values();

if ($activeModels->isEmpty()) {
    die("Tidak ada model aktif pada akun Groq\n");
}

$lastError = 'respons AI kosong';
$responseText = '';
foreach ($activeModels as $model) {
    $resp = $http->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.1,
        'max_tokens' => 1024,
    ]);
    if ($resp->successful()) {
        $responseText = $resp->json('choices.0.message.content') ?? '';
        break;
    }
    $lastError = $resp->json('error.message') ?? $resp->body();
    Log::warning("Model Groq {$model} gagal: {$lastError}");
}

if (empty($responseText)) {
    die("Gagal mendapatkan respons AI: {$lastError}\n");
}

echo "=== RESPON AI (TEFA ONLY) ===\n";
echo $responseText . "\n";
?>
