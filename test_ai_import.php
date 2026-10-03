<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WaImportDraft;
use App\Models\User;
use App\Models\TefaUnit;
use App\Services\WaImportAiService;
use App\Services\ProjectService;
use Illuminate\Support\Str;

echo "=== MEMULAI TESTING IMPORT AI ===\n";

$admin = User::where('role', 'admin_jurusan')->first();
$tefaUnitId = $admin->tefa_unit_id ?? TefaUnit::first()->id;

echo "1. Membuat Draft AI...\n";
$draft = WaImportDraft::create([
    'id' => (string) Str::uuid(),
    'uploaded_by' => $admin->id,
    'tefa_unit_id' => $tefaUnitId,
    'original_filename' => 'contoh_chat_wa_tefa.txt',
    'chat_file_path' => 'chat_tests/contoh_chat_wa_tefa.txt',
    'status' => 'processing'
]);
echo "   -> Draft ID: {$draft->id}\n";

echo "2. Memanggil Groq AI (WaImportAiService)...\n";
$aiService = app(WaImportAiService::class);
$aiService->process($draft);

$draft->refresh();
if ($draft->status === 'failed') {
    die("   -> GAGAL AI: {$draft->error_message}\n");
}

echo "   -> Berhasil mendapatkan respons AI!\n";
echo "   -> Judul Proyek: " . ($draft->ai_result['project_title'] ?? 'N/A') . "\n";
echo "   -> Harga Deal: Rp " . ($draft->ai_result['agreed_price'] ?? 'N/A') . "\n";
echo "   -> Jumlah Tasks: " . count($draft->ai_result['tasks'] ?? []) . "\n";

echo "3. Menyimpan ke Project & Tasks (ProjectService)...\n";
$projectService = app(ProjectService::class);

$validated = [
    'project_title' => $draft->ai_result['project_title'] ?? 'Untitled',
    'client_name' => $draft->ai_result['client_name'] ?? 'Klien',
    'client_contact' => $draft->ai_result['client_contact'] ?? '',
    'project_summary' => $draft->ai_result['project_summary'] ?? '',
    'agreed_price' => $draft->ai_result['agreed_price'] ?? null,
    'raw_json' => json_encode($draft->ai_result),
    'chat_file_url' => $draft->chat_file_path,
    'tasks' => $draft->ai_result['tasks'] ?? []
];

$project = $projectService->saveAiExtractionResult(
    $tefaUnitId,
    $admin->id,
    $validated,
    null // New project
);

echo "   -> BERHASIL! Proyek ID: {$project->id}\n";
echo "   -> Total Sub-tugas: {$project->tasks()->count()}\n";
echo "=== SELESAI ===\n";
