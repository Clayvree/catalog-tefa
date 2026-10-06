<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Order;
use App\Models\WorkerProfile;
use App\Enums\TaskStatus;
use App\Enums\ProjectStatus;
use Illuminate\Support\Str;

echo "============================================================\n";
echo "  TESTING FULL ALUR WORKER (dari Task Dibuat → Selesai)\n";
echo "============================================================\n\n";

// ─── Step 1: Pastikan ada Worker ───────────────────────────────
$workerUser = User::where('role', 'worker')->with('workerProfile')->first();
if (!$workerUser || !$workerUser->workerProfile) {
    die("❌ GAGAL: Tidak ada user 'worker' atau workerProfile tidak ada.\n");
}
$workerProfile = $workerUser->workerProfile;
echo "✅ Step 1: Worker ditemukan\n";
echo "   Nama : {$workerUser->name}\n";
echo "   Role : " . (is_string($workerUser->role) ? $workerUser->role : $workerUser->role->value) . "\n";
echo "   Profile ID: {$workerProfile->id}\n\n";

// ─── Step 2: Cari Task yang ditugaskan ke worker ini ──────────
$tasks = Task::with(['project', 'leader.user', 'members.user'])
    ->forWorker($workerProfile->id)
    ->get();

echo "✅ Step 2: Task yang terlihat oleh worker ini: {$tasks->count()} task\n";

if ($tasks->isEmpty()) {
    echo "   ⚠️  Worker belum punya task. Membuat task test baru...\n";

    // Buat project dummy dulu
    $adminJurusan = User::where('role', 'admin_jurusan')->first();
    $tefaUnitId = $workerProfile->tefa_unit_id;
    $project = Project::create([
        'id' => (string) Str::uuid(),
        'tefa_unit_id' => $tefaUnitId,
        'created_by' => $adminJurusan->id,
        'title' => 'Proyek Test Worker - Website Company Profile',
        'client_name' => 'Klien Test',
        'client_contact' => '081234567890',
        'description' => 'Testing alur worker secara lengkap.',
        'estimated_price' => 1800000,
        'final_price' => 1800000,
        'status' => ProjectStatus::Active,
    ]);
    echo "   -> Project dibuat: {$project->id}\n";

    // Buat task dan assign ke worker (sebagai leader)
    $task = Task::create([
        'id' => (string) Str::uuid(),
        'project_id' => $project->id,
        'title' => 'Desain UI/UX & Wireframe',
        'description' => 'Buat wireframe 5 halaman menggunakan Figma.',
        'goals' => 'Wireframe 5 halaman selesai dan disetujui klien.',
        'leader_id' => $workerProfile->id,
        'status' => TaskStatus::Todo,
        'priority' => 'high',
        'progress_percentage' => 0,
    ]);
    // Attach worker as member juga
    $task->members()->attach($workerProfile->id, ['member_task_note' => null]);
    echo "   -> Task dibuat: {$task->id}\n";
    
    // Reload
    $tasks = Task::with(['project', 'leader.user', 'members.user'])
        ->forWorker($workerProfile->id)->get();
    echo "   -> Total task setelah dibuat: {$tasks->count()}\n\n";
} else {
    foreach ($tasks as $t) {
        echo "   - [{$t->status->value}] {$t->title} (Proyek: " . ($t->project->title ?? '?') . ")\n";
    }
    echo "\n";
}

// ─── Step 3: Simulasi Worker mulai mengerjakan task ───────────
$task = $tasks->first();
echo "✅ Step 3: Simulasi Worker mengubah status ke 'in_progress'...\n";
$task->update(['status' => TaskStatus::InProgress]);
$task->refresh();
echo "   Status sekarang: {$task->status->value}\n\n";

// ─── Step 4: Simulasi Worker update progress & catatan tim ───
echo "✅ Step 4: Simulasi Worker update progress (50%) & catatan tim...\n";
$task->update([
    'team_notes' => 'Wireframe Home dan About Us sudah selesai, sedang lanjut ke halaman Services.',
    'progress_percentage' => 50,
]);
$task->refresh();
echo "   Progress: {$task->progress_percentage}%\n";
echo "   Catatan : {$task->team_notes}\n\n";

// ─── Step 5: Simulasi Worker kirim bukti & minta review ──────
echo "✅ Step 5: Simulasi Worker kirim bukti pengerjaan (Upload Proof)...\n";
$task->update([
    'proof_url'  => 'https://drive.google.com/file/d/contoh-wireframe-final',
    'proof_notes' => 'Wireframe semua halaman sudah selesai, silakan direview.',
    'status'     => TaskStatus::Review,
    'progress_percentage' => 100,
]);
$task->refresh();
echo "   Status : {$task->status->value}\n";
echo "   Bukti  : {$task->proof_url}\n";
echo "   Note   : {$task->proof_notes}\n\n";

// ─── Step 6: Simulasi Admin verifikasi & tandai Done ─────────
echo "✅ Step 6: Simulasi Admin menandai task sebagai 'done'...\n";
$task->update([
    'status'       => TaskStatus::Done,
    'completed_at' => now(),
]);
$task->refresh();
echo "   Status       : {$task->status->value}\n";
echo "   Completed At : {$task->completed_at}\n\n";

// ─── Step 7: Cek apakah semua task di proyek done → Project completed ─
$project = $task->project;
$totalTasks = $project->tasks()->count();
$doneTasks  = $project->tasks()->where('status', TaskStatus::Done->value)->count();
echo "✅ Step 7: Cek penyelesaian proyek...\n";
echo "   Total tasks : {$totalTasks}\n";
echo "   Task Done   : {$doneTasks}\n";
if ($totalTasks > 0 && $totalTasks === $doneTasks) {
    $project->update(['status' => ProjectStatus::Completed]);
    echo "   -> 🎉 Semua task selesai! Proyek otomatis ditandai COMPLETED.\n";
} else {
    echo "   -> Proyek belum selesai ({$doneTasks}/{$totalTasks} tasks done).\n";
}

echo "\n============================================================\n";
echo "  ✅ SEMUA STEP WORKER BERHASIL TANPA ERROR!\n";
echo "============================================================\n";
