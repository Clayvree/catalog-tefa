<?php

declare(strict_types=1);

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioContributor;
use App\Models\WorkerProfile;
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
            ->with(['contributors.workerProfile.user'])
            ->latest()
            ->paginate(8);

        $stats = [
            'total'    => Portfolio::where('worker_profile_id', $workerProfile->id)->count(),
            'approved' => Portfolio::where('worker_profile_id', $workerProfile->id)->where('status', PortfolioStatus::Approved->value)->count(),
            'pending'  => Portfolio::where('worker_profile_id', $workerProfile->id)->where('status', PortfolioStatus::Pending->value)->count(),
            'rejected' => Portfolio::where('worker_profile_id', $workerProfile->id)->where('status', PortfolioStatus::Rejected->value)->count(),
        ];

        // Daftar siswa se-unit untuk dropdown kontributor
        $unitWorkers = WorkerProfile::where('tefa_unit_id', $workerProfile->tefa_unit_id)
            ->where('id', '!=', $workerProfile->id) // exclude diri sendiri
            ->with('user')
            ->get();

        return view('worker.portfolios.index', compact('portfolios', 'workerProfile', 'stats', 'unitWorkers'));
    }

    public function store(Request $request)
    {
        $workerProfile = $request->user()->workerProfile;

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:2000',
            'external_link'  => 'nullable|url|max:255',
            'thumbnail_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',

            // Kontributor tambahan (opsional, bisa lebih dari satu)
            'contributors'                      => 'nullable|array|max:10',
            'contributors.*.worker_profile_id'  => 'nullable|uuid|exists:worker_profiles,id',
            'contributors.*.guest_name'         => 'nullable|string|max:100',
            'contributors.*.role'               => 'nullable|string|max:100',
        ]);

        $path = $request->file('thumbnail_file')->store('portfolios', 'public');
        $thumbnailUrl = '/storage/' . $path;

        $portfolio = Portfolio::create([
            'id'               => (string) Str::uuid(),
            'worker_profile_id'=> $workerProfile->id,
            'tefa_unit_id'     => $workerProfile->tefa_unit_id,
            'title'            => $validated['title'],
            'description'      => $validated['description'],
            'external_link'    => $validated['external_link'] ?? null,
            'thumbnail_url'    => $thumbnailUrl,
            'status'           => PortfolioStatus::Pending,
        ]);

        // Simpan kontributor tambahan
        $this->syncContributors($portfolio, $validated['contributors'] ?? []);

        return redirect()->back()->with('success', 'Pengajuan karya portofolio berhasil dikirim! Menunggu persetujuan Admin Jurusan.');
    }

    /**
     * Update daftar kontributor untuk portofolio yang sudah ada.
     */
    public function updateContributors(Request $request, Portfolio $portfolio)
    {
        if ($portfolio->worker_profile_id !== auth()->user()->workerProfile?->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'contributors'                      => 'nullable|array|max:10',
            'contributors.*.worker_profile_id'  => 'nullable|uuid|exists:worker_profiles,id',
            'contributors.*.guest_name'         => 'nullable|string|max:100',
            'contributors.*.role'               => 'nullable|string|max:100',
        ]);

        // Hapus semua kontributor lama, ganti dengan yang baru
        $portfolio->contributors()->delete();
        $this->syncContributors($portfolio, $validated['contributors'] ?? []);

        return redirect()->back()->with('success', 'Daftar kontributor berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->worker_profile_id !== auth()->user()->workerProfile?->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $portfolio->delete();
        return redirect()->back()->with('success', 'Karya portofolio berhasil dihapus.');
    }

    // --- Helpers -------------------------------------------------
    private function syncContributors(Portfolio $portfolio, array $contributors): void
    {
        foreach ($contributors as $item) {
            $workerProfileId = $item['worker_profile_id'] ?? null;
            $guestName       = $item['guest_name'] ?? null;

            // Harus ada salah satu: akun atau nama tamu
            if (!$workerProfileId && !$guestName) {
                continue;
            }

            PortfolioContributor::create([
                'id'                => (string) Str::uuid(),
                'portfolio_id'      => $portfolio->id,
                'worker_profile_id' => $workerProfileId ?: null,
                'guest_name'        => $workerProfileId ? null : $guestName,
                'role'              => $item['role'] ?? null,
            ]);
        }
    }
}