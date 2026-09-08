<?php

declare(strict_types=1);

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Enums\PortfolioStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $workerProfile = $request->user()->workerProfile;

        if (!$workerProfile) {
            return redirect()->route('worker.dashboard')->with('error', 'Profil siswa belum lengkap.');
        }

        $portfolios = Portfolio::where('worker_profile_id', $workerProfile->id)
            ->latest()
            ->paginate(8);

        $stats = [
            'total' => Portfolio::where('worker_profile_id', $workerProfile->id)->count(),
            'approved' => Portfolio::where('worker_profile_id', $workerProfile->id)->where('status', PortfolioStatus::Approved->value)->count(),
            'pending' => Portfolio::where('worker_profile_id', $workerProfile->id)->where('status', PortfolioStatus::Pending->value)->count(),
            'rejected' => Portfolio::where('worker_profile_id', $workerProfile->id)->where('status', PortfolioStatus::Rejected->value)->count(),
        ];

        return view('worker.portfolios.index', compact('portfolios', 'workerProfile', 'stats'));
    }

    public function store(Request $request)
    {
        $workerProfile = $request->user()->workerProfile;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'external_link' => 'nullable|url|max:255',
            'thumbnail_url' => 'nullable|url|max:500',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $thumbnailUrl = $validated['thumbnail_url'] ?? 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80';

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('portfolios', 'public');
            $thumbnailUrl = '/storage/' . $path;
        }

        Portfolio::create([
            'id' => (string) Str::uuid(),
            'worker_profile_id' => $workerProfile->id,
            'tefa_unit_id' => $workerProfile->tefa_unit_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'external_link' => $validated['external_link'] ?? null,
            'thumbnail_url' => $thumbnailUrl,
            'status' => PortfolioStatus::Pending,
        ]);

        return redirect()->back()->with('success', 'Pengajuan karya portofolio berhasil dikirim! Menunggu persetujuan Admin Jurusan.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->worker_profile_id !== auth()->user()->workerProfile?->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $portfolio->delete();
        return redirect()->back()->with('success', 'Karya portofolio berhasil dihapus.');
    }
}