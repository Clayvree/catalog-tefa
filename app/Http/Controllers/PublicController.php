<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TefaUnit;
use App\Models\CatalogItem;
use App\Models\Portfolio;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $categories = Category::withCount('catalogItems')->get();
        $units = TefaUnit::withCount(['catalogItems', 'portfolios'])->where('is_active', true)->get();
        
        // Hanya 4 Top Produk Unggulan di Beranda
        $featuredItems = CatalogItem::with(['category', 'tefaUnit'])->published()->latest()->take(4)->get();
        $featuredPortfolios = Portfolio::with(['worker.user', 'tefaUnit'])->where('status', 'approved')->latest()->take(2)->get();
        
        $stats = [
            'total_units' => $units->count(),
            'total_products' => CatalogItem::published()->count(),
            'total_portfolios' => Portfolio::where('status', 'approved')->count(),
            'total_workers' => \App\Models\WorkerProfile::count(),
        ];

        return view('public.home', compact('categories', 'units', 'featuredItems', 'featuredPortfolios', 'stats'));
    }

    public function about()
    {
        $stats = [
            'total_units' => TefaUnit::where('is_active', true)->count(),
            'total_products' => CatalogItem::published()->count(),
            'total_portfolios' => Portfolio::where('status', 'approved')->count(),
        ];
        return view('public.about', compact('stats'));
    }

    public function jurusanList()
    {
        $units = TefaUnit::withCount(['catalogItems', 'portfolios'])->where('is_active', true)->paginate(9);
        return view('public.jurusan_list', compact('units'));
    }

    public function produkList(Request $request)
    {
        $query = CatalogItem::with(['category', 'tefaUnit'])->published();
        
        // Search
        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter Category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter Type (produk, jasa, kegiatan)
        if ($request->filled('type')) {
            $query->where('item_type', $request->type);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        match($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $items = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('catalogItems')->orderBy('name')->get();
        $units = TefaUnit::where('is_active', true)->get();

        return view('public.produk_list', compact('items', 'categories', 'units'));
    }

    public function storefront(string $slug)
    {
        $unit = TefaUnit::withCount(['catalogItems', 'portfolios'])->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $featuredItems = CatalogItem::with('category')->forUnit($unit->id)->published()->latest()->take(6)->get();
        $featuredPortfolios = Portfolio::with('worker.user')->where('tefa_unit_id', $unit->id)->where('status', 'approved')->latest()->take(4)->get();
        $categories = Category::whereHas('catalogItems', fn($q) => $q->where('tefa_unit_id', $unit->id))->get();

        return view('public.storefront', compact('unit', 'featuredItems', 'featuredPortfolios', 'categories'));
    }

    public function catalog(string $slug, Request $request)
    {
        $unit = TefaUnit::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $query = CatalogItem::with('category')->forUnit($unit->id)->published();
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('item_type', $request->type);
        }

        $items = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        
        return view('public.catalog', compact('unit', 'items', 'categories'));
    }

    public function portfolio(string $slug)
    {
        $unit = TefaUnit::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $portfolios = Portfolio::with(['worker.user'])->where('tefa_unit_id', $unit->id)->where('status', 'approved')->latest()->paginate(12);
        return view('public.portfolio', compact('unit', 'portfolios'));
    }
}
