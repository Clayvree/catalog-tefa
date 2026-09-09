<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TefaUnit;
use App\Models\Category;
use App\Models\User;
use App\Models\Project;
use App\Models\CatalogItem;
use App\Models\WorkerProfile;
use App\Models\Portfolio;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_units' => TefaUnit::count(),
            'total_products' => CatalogItem::count(),
            'total_projects' => Project::count(),
            'total_workers' => WorkerProfile::count(),
            'total_categories' => Category::count(),
            'total_portfolios' => Portfolio::where('status', 'approved')->count(),
            'total_revenue_est' => Project::sum('final_price') ?? 0,
        ];

        $recentProjects = Project::with(['tefaUnit', 'creator'])->latest()->take(5)->get();
        $topUnits = TefaUnit::withCount(['catalogItems', 'workerProfiles'])->get();

        return view('superadmin.dashboard', compact('stats', 'recentProjects', 'topUnits'));
    }
}