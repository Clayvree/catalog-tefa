<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWhatsAppChatJob;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WhatsAppImportController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    public function create(Request $request)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        return view('admin.projects.wa_import', ['tefaUnitId' => $tefaUnitId]);
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

        ProcessWhatsAppChatJob::dispatch(
            $path,
            $request->input('tefa_unit_id'),
            $request->user()->id
        );

        return redirect()->back()->with('success', 'File chat sedang diproses AI di background.');
    }

    public function confirm(Request $request)
    {
        // (Logika konfirmasi sama seperti sebelumnya)
    }
}
