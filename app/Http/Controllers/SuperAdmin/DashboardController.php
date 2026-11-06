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

        // Chart Data: Breakdown per unit
        $unitLabels = [];
        $unitCatalogCounts = [];
        $unitWorkerCounts = [];
        $unitRevenue = [];

        foreach ($topUnits as $unit) {
            $unitLabels[] = $unit->name;
            $unitCatalogCounts[] = $unit->catalog_items_count;
            $unitWorkerCounts[] = $unit->worker_profiles_count;
            $unitRevenue[] = (float) (Project::where('tefa_unit_id', $unit->id)->sum('final_price') ?? 0);
        }

        // Chart Data: Status Proyek
        $projectStatusCounts = [
            'Aktif' => Project::where('status', 'active')->count(),
            'Draft' => Project::where('status', 'draft')->count(),
            'Selesai' => Project::where('status', 'completed')->count(),
            'Batal' => Project::where('status', 'cancelled')->count(),
        ];

        // Chart Data: Tren Bulanan (6 bulan terakhir)
        $monthlyLabels = [];
        $monthlyRevenue = [];
        $monthlyProjects = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabel = $month->translatedFormat('M Y');
            $monthlyLabels[] = $monthLabel;
            $monthlyRevenue[] = (float) (Project::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('final_price') ?? 0);
            $monthlyProjects[] = Project::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $chartData = [
            'units' => [
                'labels' => $unitLabels,
                'catalogs' => $unitCatalogCounts,
                'workers' => $unitWorkerCounts,
                'revenue' => $unitRevenue,
            ],
            'status' => [
                'labels' => array_keys($projectStatusCounts),
                'counts' => array_values($projectStatusCounts),
            ],
            'monthly' => [
                'labels' => $monthlyLabels,
                'revenue' => $monthlyRevenue,
                'projects' => $monthlyProjects,
            ],
        ];

        return view('superadmin.dashboard', compact('stats', 'recentProjects', 'topUnits', 'chartData'));
    }

    public function statsReport()
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

        $units = TefaUnit::withCount(['catalogItems', 'workerProfiles'])
            ->with(['catalogItems' => fn($q) => $q->published()])
            ->get()
            ->map(function ($unit) {
                $unit->total_revenue = Project::where('tefa_unit_id', $unit->id)->sum('final_price') ?? 0;
                $unit->total_projects = Project::where('tefa_unit_id', $unit->id)->count();
                return $unit;
            });

        $projects = Project::with(['tefaUnit'])->latest()->get();
        $categories = Category::withCount('catalogItems')->orderBy('name')->get();

        // Chart Data for PDF
        $monthlyLabels = [];
        $monthlyRevenue = [];
        $monthlyProjects = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->translatedFormat('M Y');
            $monthlyRevenue[] = (float) (Project::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('final_price') ?? 0);
            $monthlyProjects[] = Project::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $chartData = [
            'monthly' => [
                'labels' => $monthlyLabels,
                'revenue' => $monthlyRevenue,
                'projects' => $monthlyProjects,
            ],
        ];

        return view('superadmin.stats-report', compact('stats', 'units', 'projects', 'categories', 'chartData'));
    }

    public function catalogBrochure()
    {
        $units = TefaUnit::query()
            ->where('is_active', true)
            ->whereHas('catalogItems', fn ($query) => $query->published())
            ->with(['catalogItems' => fn ($query) => $query->published()->with('category')->orderBy('title')])
            ->orderBy('name')
            ->get();

        return view('superadmin.catalog-brochure', compact('units'));
    }
}