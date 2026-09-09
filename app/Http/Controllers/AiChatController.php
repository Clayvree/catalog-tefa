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
    private string $geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

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

                $routerResponse = $this->callGemini($routerPrompt);

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
PROMPT;

            $aiText = $this->callGemini($finalPrompt);

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

    private function callGemini(string $prompt): string
    {
        $apiKey  = env('GEMINI_API_KEY');
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(30)
            ->post("{$this->geminiEndpoint}?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]],
            ]);

        if ($response->failed()) {
            Log::error('Gemini API Error Body: ' . $response->body());
            throw new \Exception('Gemini API Error: ' . $response->status());
        }

        return $response->json('candidates.0.content.parts.0.text')
            ?? 'Maaf, saya sedang mengalami gangguan.';
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

                $items = CatalogItem::published()->forUnit($tefaUnitId)->take(8)->get(['title', 'price', 'item_type']);
                if ($items->isNotEmpty()) {
                    $context .= "Produk/Jasa tersedia:\n";
                    foreach ($items as $item) {
                        $harga = $item->price ? 'Rp ' . number_format((float) $item->price, 0, ',', '.') : 'Hubungi kami';
                        $context .= "- {$item->title} ({$harga})\n";
                    }
                }
            }
        } else {
            $units = TefaUnit::where('is_active', true)->pluck('name')->toArray();
            $context .= "Jurusan tersedia: " . implode(', ', $units) . "\n";
        }

        return $context;
    }

    private function getChatHistory(string $sessionId): string
    {
        $messages = AiChatMessage::where('session_id', $sessionId)
            ->latest()
            ->take(8) // 4 putaran percakapan
            ->get()
            ->reverse();

        return $messages->map(function ($msg) {
            $sender = $msg->sender === 'user' ? 'User' : 'AI';
            return "{$sender}: {$msg->message}";
        })->implode("\n");
    }
}

