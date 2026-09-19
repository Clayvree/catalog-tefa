<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Models\AiKnowledgeBase;
use App\Models\CatalogItem;
use App\Models\TefaUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    private string $groqEndpoint = 'https://api.groq.com/openai/v1/chat/completions';

    public function message(Request $request)
    {
        $request->validate([
            'message'      => 'required|string|max:1000',
            'tefa_unit_id' => 'nullable|uuid',
        ]);

        $userMessage = $request->input('message');
        $tefaUnitId  = $request->input('tefa_unit_id');
        $userId      = $request->user()?->id;

        try {
            $tefaUnitId = $this->resolveTefaUnitId($tefaUnitId, $userMessage);

            // ── 1. Resolve / buat session ────────────────────────────────
            if ($userId) {
                $session = AiChatSession::firstOrCreate(
                    ['user_id' => $userId, 'tefa_unit_id' => $tefaUnitId],
                    ['session_token' => (string) Str::uuid()]
                );
            } else {
                $sessionToken = $request->session()->getId();
                $session = AiChatSession::firstOrCreate(
                    ['session_token' => $sessionToken, 'tefa_unit_id' => $tefaUnitId]
                );
            }

            // ── 2. Simpan pesan user ─────────────────────────────────────
            AiChatMessage::create([
                'session_id' => $session->id,
                'sender'     => 'user',
                'message'    => $userMessage,
            ]);

            // ── 3. STEP 1 – AI Routing: pilih knowledge yang relevan ──────
            $knowledgeIndex = AiKnowledgeBase::forUnit($tefaUnitId)->get(['id', 'title']);

            $selectedDescriptions = collect();

            if ($knowledgeIndex->isNotEmpty()) {
                $indexList = $knowledgeIndex->map(fn($k) => "[{$k->id}] {$k->title}")->implode("\n");

                $routerPrompt = <<<PROMPT
Kamu adalah sistem router AI. Tugasmu HANYA menentukan ID knowledge yang dibutuhkan untuk menjawab pertanyaan berikut.

Pertanyaan user: "{$userMessage}"

Daftar Knowledge yang tersedia (format [ID] Judul):
{$indexList}

Jawab HANYA dengan JSON array berisi ID yang relevan. Contoh: [1, 3]
Jika tidak ada yang relevan, jawab: []
Jangan tambahkan penjelasan apapun.
PROMPT;

                $routerResponse = $this->callGroq($routerPrompt);

                // Parse JSON array dari balasan AI router
                preg_match('/\[[\d,\s]*\]/', $routerResponse, $matches);
                $selectedIds = json_decode($matches[0] ?? '[]', true) ?? [];

                if (!empty($selectedIds)) {
                    $selectedDescriptions = AiKnowledgeBase::whereIn('id', $selectedIds)
                        ->where('is_active', true)
                        ->get(['title', 'description']);
                }

                Log::info('AI Router selected knowledge IDs: ' . json_encode($selectedIds));
            }

            // ── 4. STEP 2 – Rakit Final Prompt & Jawab ───────────────────
            $knowledgeContext = '';
            if ($selectedDescriptions->isNotEmpty()) {
                $knowledgeContext = "=== Informasi Relevan ===\n";
                foreach ($selectedDescriptions as $kb) {
                    $knowledgeContext .= "## {$kb->title}\n{$kb->description}\n\n";
                }
            }

            $platformContext = $this->buildPlatformContext($tefaUnitId);
            $chatHistory     = $this->getChatHistory($session->id);

            $finalPrompt = <<<PROMPT
Kamu adalah AI Assistant yang ramah dan profesional untuk platform Teaching Factory (TEFA) SMK.

{$platformContext}

{$knowledgeContext}
=== Riwayat Percakapan ===
{$chatHistory}

=== Pertanyaan Terbaru ===
User: {$userMessage}

Jawab dalam Bahasa Indonesia yang ramah dan profesional. Gunakan informasi relevan di atas jika tersedia.
Jika user menanyakan produk, sebutkan unit TEFA yang sesuai dan tampilkan daftar produk yang relevan dari konteks.
Gunakan angka stok persis dari konteks. Jangan mengarang produk, harga, atau stok. Jika stok 0, katakan produk sedang habis.
PROMPT;

            $aiText = $this->callGroq($finalPrompt);

            // ── 5. Simpan & kembalikan balasan ────────────────────────────
            AiChatMessage::create([
                'session_id' => $session->id,
                'sender'     => 'ai',
                'message'    => $aiText,
            ]);

            return response()->json([
                'session_token' => $session->session_token,
                'reply'         => $aiText,
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, sistem AI kami sedang tidak tersedia saat ini.',
            ], 500);
        }
    }

    // ── Private Helpers ───────────────────────────────────────────────────

    private function resolveTefaUnitId(?string $requestedUnitId, string $userMessage): ?string
    {
        if ($requestedUnitId) {
            return TefaUnit::where('id', $requestedUnitId)
                ->where('is_active', true)
                ->value('id');
        }

        $units = TefaUnit::where('is_active', true)->get(['id', 'name', 'description']);
        if ($units->isEmpty()) {
            return null;
        }

        $unitList = $units->map(function (TefaUnit $unit) {
            return "[{$unit->id}] {$unit->name}: " . Str::limit((string) $unit->description, 180);
        })->implode("\n");

        $routerPrompt = <<<PROMPT
Kamu adalah router TEFA. Pilih satu unit TEFA yang paling relevan dengan pertanyaan user.

Pertanyaan user: "{$userMessage}"

Daftar unit aktif:
{$unitList}

Jawab HANYA dengan JSON object berikut:
{"tefa_unit_id":"UUID"}

Jika tidak ada unit yang relevan, jawab {"tefa_unit_id":null}.
PROMPT;

        $routerResponse = $this->callGroq($routerPrompt);
        $json = json_decode(trim($routerResponse), true);
        $selectedId = is_array($json) ? ($json['tefa_unit_id'] ?? null) : null;

        if (!$selectedId && preg_match('/[0-9a-f]{8}-[0-9a-f-]{27,}/i', $routerResponse, $matches)) {
            $selectedId = $matches[0];
        }

        $resolvedId = $units->firstWhere('id', $selectedId)?->id;
        Log::info('AI Router selected TEFA unit: ' . ($resolvedId ?? 'none'));

        return $resolvedId;
    }

    /**
     * Helper Groq dengan fitur Auto-Detect Model yang Aktif
     */
    private function callGroq(string $prompt): string
    {
        $apiKey = trim((string) env('GROQ_API_KEY'));

        if (empty($apiKey)) {
            throw new \Exception('GROQ_API_KEY tidak ditemukan pada file .env');
        }

        $http = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(20);

        if (app()->environment('local')) {
            $http->withoutVerifying();
        }

        // 1. Ambil daftar model yang AKTIF secara otomatis dari API Groq
        $modelsResponse = $http->get('https://api.groq.com/openai/v1/models');

        if ($modelsResponse->failed()) {
            Log::error('Gagal mengambil daftar model Groq: ' . $modelsResponse->body());
            throw new \Exception('GROQ API Key tidak valid / terblokir.');
        }

        $activeModels = collect($modelsResponse->json('data', []))
            ->pluck('id')
            ->filter(fn($id) => !str_contains($id, 'whisper') && !str_contains($id, 'safetensors')) // Filter hanya model text chat
            ->values();

        if ($activeModels->isEmpty()) {
            throw new \Exception('Tidak ada model text chat yang aktif di akun Groq ini.');
        }

        // 2. Coba kirim request ke model aktif satu per satu sampai berhasil
        foreach ($activeModels as $model) {
            $response = $http->post($this->groqEndpoint, [
                'model'    => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.5,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content')
                    ?? 'Maaf, saya sedang mengalami gangguan.';
            }

            Log::warning("Model Groq {$model} gagal: " . ($response->json('error.message') ?? $response->body()));
        }

        throw new \Exception('Semua model aktif Groq gagal memproses request.');
    }

    private function buildPlatformContext(?string $tefaUnitId): string
    {
        $context = "=== Konteks Platform ===\n";
        $context .= "Platform: Teaching Factory (TEFA) SMK\n";

        if ($tefaUnitId) {
            $unit = TefaUnit::find($tefaUnitId);
            if ($unit) {
                $context .= "Jurusan: {$unit->name}\n";
                $context .= "Deskripsi: {$unit->description}\n";

                $items = CatalogItem::published()
                    ->forUnit($tefaUnitId)
                    ->with('category:id,name')
                    ->orderByDesc('stock')
                    ->orderBy('title')
                    ->take(8)
                    ->get(['id', 'category_id', 'title', 'price', 'item_type', 'stock']);
                if ($items->isNotEmpty()) {
                    $context .= "Produk/Jasa tersedia:\n";
                    foreach ($items as $item) {
                        $harga = $item->price ? 'Rp ' . number_format((float) $item->price, 0, ',', '.') : 'Hubungi kami';
                        $stok = $item->item_type?->value === 'jasa'
                            ? 'layanan jasa, konsultasi diperlukan'
                            : ($item->stock > 0 ? "tersedia, stok {$item->stock}" : 'habis, stok 0');
                        $kategori = $item->category?->name ? ", kategori {$item->category->name}" : '';
                        $context .= "- {$item->title}{$kategori} ({$harga}; {$stok})\n";
                    }
                } else {
                    $context .= "Produk/Jasa tersedia: belum ada katalog published untuk unit ini.\n";
                }
            }
        } else {
            $units = TefaUnit::where('is_active', true)->get(['id', 'name']);
            $context .= "Jurusan tersedia: " . $units->pluck('name')->implode(', ') . "\n";
            $context .= "Katalog produk per jurusan:\n";

            foreach ($units as $unit) {
                $context .= "## {$unit->name}\n";
                $items = CatalogItem::published()
                    ->forUnit($unit->id)
                    ->orderByDesc('stock')
                    ->orderBy('title')
                    ->take(8)
                    ->get(['title', 'price', 'item_type', 'stock']);

                if ($items->isEmpty()) {
                    $context .= "- Belum ada produk published.\n";
                    continue;
                }

                foreach ($items as $item) {
                    $harga = $item->price ? 'Rp ' . number_format((float) $item->price, 0, ',', '.') : 'Hubungi kami';
                    $stok = $item->item_type?->value === 'jasa'
                        ? 'layanan jasa, konsultasi diperlukan'
                        : ($item->stock > 0 ? "tersedia, stok {$item->stock}" : 'habis, stok 0');
                    $context .= "- {$item->title} ({$harga}; {$stok})\n";
                }
            }
        }

        return $context;
    }

    private function getChatHistory(string $sessionId): string
    {
        $messages = AiChatMessage::where('session_id', $sessionId)
            ->latest()
            ->take(8)
            ->get()
            ->reverse();

        return $messages->map(function ($msg) {
            $sender = $msg->sender === 'user' ? 'User' : 'AI';
            return "{$sender}: {$msg->message}";
        })->implode("\n");
    }
}