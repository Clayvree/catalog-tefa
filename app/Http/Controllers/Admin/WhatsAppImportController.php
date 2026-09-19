<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaImportDraft;
use App\Services\ProjectService;
use App\Services\WaImportAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WhatsAppImportController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly WaImportAiService $aiService
    ) {}

    public function create(Request $request)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        
        $drafts = WaImportDraft::where('tefa_unit_id', $tefaUnitId)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.projects.wa_import', compact('tefaUnitId', 'drafts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'chat_file' => 'required|file|mimes:txt|max:5120',
            'tefa_unit_id' => 'required|uuid'
        ]);

        $file = $request->file('chat_file');
        $filename = 'wa_chat_' . Str::random(10) . '_' . time() . '.txt';
        $path = $file->storeAs('whatsapp_imports', $filename, 'local');

        // Create draft
        $draft = WaImportDraft::create([
            'tefa_unit_id' => $request->input('tefa_unit_id'),
            'uploaded_by' => $request->user()->id,
            'chat_file_path' => $path,
            'status' => 'processing',
        ]);

        // Process directly (sync)
        $this->aiService->process($draft);

        if ($draft->status === 'failed') {
            return redirect()->back()->with('error', 'Gagal memproses AI: ' . $draft->error_message);
        }

        return redirect()->route('admin.projects.import-wa.review', $draft->id)
            ->with('success', 'Ekstraksi AI berhasil. Silakan tinjau hasilnya.');
    }

    public function review(Request $request, WaImportDraft $draft)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($draft->tefa_unit_id !== $tefaUnitId, 403);

        if ($draft->status !== 'ready') {
            return redirect()->route('admin.projects.import-wa.create')
                ->with('error', 'Draft ini tidak dalam status siap direview.');
        }

        return view('admin.projects.wa_review', compact('draft'));
    }

    public function confirm(Request $request, WaImportDraft $draft)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($draft->tefa_unit_id !== $tefaUnitId, 403);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:50',
            'project_title' => 'required|string|max:255',
            'project_summary' => 'required|string',
            'agreed_price' => 'nullable|numeric|min:0',
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.instructions' => 'required|string',
            'tasks.*.reasoning' => 'nullable|string',
        ]);

        // Add additional data needed by service
        $validated['chat_file_url'] = $draft->chat_file_path; // Or proper public URL if needed
        $validated['raw_json'] = $draft->ai_result;

        // Save using service
        $project = $this->projectService->saveAiExtractionResult(
            $tefaUnitId,
            $request->user()->id,
            $validated
        );

        // Update draft status
        $draft->update(['status' => 'confirmed']);

        return redirect()->route('admin.projects.show', $project->id)
            ->with('success', 'Proyek berhasil dibuat dari WhatsApp Import!');
    }
}
