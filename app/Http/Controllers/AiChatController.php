<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Models\CatalogItem;
use App\Models\TefaUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    /**
     * Handle public chat widget message using Gemini.
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_token' => 'nullable|string',
            'tefa_unit_id' => 'nullable|uuid'
        ]);

        $userMessage = $request->input('message');
        $sessionToken = $request->input('session_token') ?? (string) Str::uuid();
        $tefaUnitId = $request->input('tefa_unit_id');

        try {
            // 1. Get or create session
            $session = AiChatSession::firstOrCreate(
                ['session_token' => $sessionToken],
                [
                    'user_id' => $request->user()?->id,
                    'tefa_unit_id' => $tefaUnitId
                ]
            );

            // 2. Simpan pesan user
            AiChatMessage::create([
                'session_id' => $session->id,
                'sender' => 'user',
                'message' => $userMessage
            ]);

            // 3. Bangun context (RAG sederhana)
            $context = $this->buildContext($tefaUnitId);
            $history = $this->getChatHistory($session->id);

            // 4. Panggil Gemini
            $geminiApiKey = env('GEMINI_API_KEY');
            
            $prompt = "Context Platform:\n{$context}\n\nRiwayat Chat:\n{$history}\n\nUser: {$userMessage}\n\nAI Assistant (jawab dalam bahasa Indonesia, ramah, dan profesional):";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiApiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API Error');
            }

            $responseData = $response->json();
            $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya sedang mengalami gangguan.';

            // 5. Simpan balasan AI
            AiChatMessage::create([
                'session_id' => $session->id,
                'sender' => 'ai',
                'message' => $aiText
            ]);

            return response()->json([
                'session_token' => $sessionToken,
                'reply' => $aiText
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, sistem AI kami sedang tidak tersedia saat ini.'
            ], 500);
        }
    }

    private function buildContext(?string $tefaUnitId): string
    {
        $context = "Anda adalah AI Assistant untuk platform Teaching Factory (TEFA).\n";
        
        if ($tefaUnitId) {
            $unit = TefaUnit::find($tefaUnitId);
            if ($unit) {
                $context .= "Saat ini Anda melayani pertanyaan untuk jurusan: {$unit->name}.\n";
                $context .= "Deskripsi jurusan: {$unit->description}\n";
                
                $items = CatalogItem::published()->forUnit($tefaUnitId)->take(10)->get();
                if ($items->isNotEmpty()) {
                    $context .= "Produk/Jasa yang tersedia:\n";
                    foreach ($items as $item) {
                        $context .= "- {$item->title} (Rp " . number_format((float)$item->price, 0, ',', '.') . ")\n";
                    }
                }
            }
        } else {
            $context .= "Anda melayani portal utama. Tersedia berbagai layanan dari berbagai jurusan.\n";
            $units = TefaUnit::where('is_active', true)->pluck('name')->toArray();
            $context .= "Jurusan yang ada: " . implode(', ', $units) . ".\n";
        }

        return $context;
    }

    private function getChatHistory(string $sessionId): string
    {
        $messages = AiChatMessage::where('session_id', $sessionId)
            ->latest()
            ->take(6) // Ambil 6 pesan terakhir agar context window tidak penuh
            ->get()
            ->reverse();

        $history = "";
        foreach ($messages as $msg) {
            $sender = $msg->sender === 'user' ? 'User' : 'AI';
            $history .= "{$sender}: {$msg->message}\n";
        }
        
        return $history;
    }
}
