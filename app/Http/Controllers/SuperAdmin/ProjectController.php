<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TefaUnit;
use App\Enums\ProjectStatus;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['tefaUnit', 'tasks.leader.user', 'tasks.members.user', 'creator']);

        if ($request->filled('unit')) {
            $query->where('tefa_unit_id', $request->unit);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('client_name', 'like', "%{$keyword}%");
            });
        }

        $projects = $query->latest()->paginate(12)->withQueryString();
        $units = TefaUnit::all();

        $stats = [
            'total' => Project::count(),
            'active' => Project::where('status', ProjectStatus::Active->value)->count(),
            'completed' => Project::where('status', ProjectStatus::Completed->value)->count(),
            'total_revenue' => Project::sum('final_price') ?? 0,
        ];

        return view('superadmin.projects.index', compact('projects', 'units', 'stats'));
    }
}