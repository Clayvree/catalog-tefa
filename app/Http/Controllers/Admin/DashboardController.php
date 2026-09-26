<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TefaUnit;
use App\Models\Project;
use App\Models\Task;
use App\Models\WorkerProfile;
use App\Models\Portfolio;
use App\Models\CatalogItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $managedUnit = $user->managedUnits()->first();

        if (!$managedUnit) {
            // Fallback if superadmin views or unit not linked yet
            $managedUnit = TefaUnit::first();
        }

        $unitId = $managedUnit?->id;

        $stats = [
            'total_projects' => $unitId ? Project::forUnit($unitId)->count() : 0,
            'active_projects' => $unitId ? Project::forUnit($unitId)->where('status', 'active')->count() : 0,
            'total_tasks' => $unitId ? Task::where('tefa_unit_id', $unitId)->count() : 0,
            'total_workers' => $unitId ? WorkerProfile::where('tefa_unit_id', $unitId)->count() : 0,
            'pending_portfolios' => $unitId ? Portfolio::where('tefa_unit_id', $unitId)->where('status', 'pending')->count() : 0,
        ];

        $projects = $unitId ? Project::with(['tasks.leader.user', 'tasks.members.user', 'creator'])->forUnit($unitId)->latest()->take(6)->get() : collect();
        $workers = $unitId ? WorkerProfile::with(['user', 'ledTasks' => fn($q) => $q->where('status', '!=', 'done'), 'memberTasks' => fn($q) => $q->where('status', '!=', 'done')])->where('tefa_unit_id', $unitId)->get() : collect();
        $pendingPortfolios = $unitId ? Portfolio::with('worker.user')->where('tefa_unit_id', $unitId)->where('status', 'pending')->latest()->get() : collect();
        $catalogItems = $unitId ? CatalogItem::forUnit($unitId)->latest()->take(5)->get() : collect();

        return view('admin.dashboard', compact('managedUnit', 'stats', 'projects', 'workers', 'pendingPortfolios', 'catalogItems'));
    }
}