<?php

declare(strict_types=1);

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Portfolio;
use App\Models\WorkerProfile;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Enums\PortfolioStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->workerProfile;

        // Auto-create profile if missing
        if (!$profile) {
            $unit = \App\Models\TefaUnit::first();
            $profile = WorkerProfile::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'tefa_unit_id' => $unit?->id ?? (string) Str::uuid(),
                'class_name' => 'Siswa Vokasi',
                'bio' => 'Talenta TEFA Siswa Development',
            ]);
        }
        
        $profile->load(['tefaUnit', 'skills']);
        $workerId = $profile->id;

        $totalAssigned = Task::forWorker($workerId)->count();
        $completedTasks = Task::forWorker($workerId)->where('status', TaskStatus::Done->value)->count();
        $completionRate = $totalAssigned > 0 ? round(($completedTasks / $totalAssigned) * 100) : 100;

        $stats = [
            'total_assigned' => $totalAssigned,
            'todo_tasks' => Task::forWorker($workerId)->where('status', TaskStatus::Todo->value)->count(),
            'in_progress_tasks' => Task::forWorker($workerId)->where('status', TaskStatus::InProgress->value)->count(),
            'review_tasks' => Task::forWorker($workerId)->where('status', TaskStatus::Review->value)->count(),
            'completed_tasks' => $completedTasks,
            'completion_rate' => $completionRate,
            
            // Priority breakdown
            'high_priority' => Task::forWorker($workerId)->where('priority', TaskPriority::High->value)->where('status', '!=', TaskStatus::Done->value)->count(),
            'medium_priority' => Task::forWorker($workerId)->where('priority', TaskPriority::Medium->value)->where('status', '!=', TaskStatus::Done->value)->count(),
            'low_priority' => Task::forWorker($workerId)->where('priority', TaskPriority::Low->value)->where('status', '!=', TaskStatus::Done->value)->count(),
            
            // Portfolios
            'portfolios_approved' => Portfolio::where('worker_profile_id', $workerId)->where('status', PortfolioStatus::Approved->value)->count(),
            'portfolios_pending' => Portfolio::where('worker_profile_id', $workerId)->where('status', PortfolioStatus::Pending->value)->count(),
        ];

        $urgentTasks = Task::with(['project', 'skill'])
            ->forWorker($workerId)
            ->where('status', '!=', TaskStatus::Done->value)
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->take(5)
            ->get();

        $recentPortfolios = Portfolio::where('worker_profile_id', $workerId)->latest()->take(4)->get();

        return view('worker.dashboard', compact('profile', 'stats', 'urgentTasks', 'recentPortfolios'));
    }
}