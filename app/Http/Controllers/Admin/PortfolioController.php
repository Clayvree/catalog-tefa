<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioContributor;
use App\Models\WorkerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Enums\PortfolioStatus;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        
        $status = $request->query('status', 'all');

        $query = Portfolio::with(['worker.user', 'contributors.workerProfile.user'])
            ->where('tefa_unit_id', $tefaUnitId)
            ->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $portfolios = $query->paginate(12)->appends(['status' => $status]);

        $stats = [
            'all' => Portfolio::where('tefa_unit_id', $tefaUnitId)->count(),
            'pending' => Portfolio::where('tefa_unit_id', $tefaUnitId)->where('status', PortfolioStatus::Pending)->count(),
            'approved' => Portfolio::where('tefa_unit_id', $tefaUnitId)->where('status', PortfolioStatus::Approved)->count(),
            'rejected' => Portfolio::where('tefa_unit_id', $tefaUnitId)->where('status', PortfolioStatus::Rejected)->count(),
        ];

        // Daftar worker/siswa terdaftar di unit ini untuk pilihan dropdown
        $unitWorkers = WorkerProfile::where('tefa_unit_id', $tefaUnitId)->with('user')->get();

        return view('admin.portfolios.index', compact('portfolios', 'status', 'stats', 'unitWorkers'));
    }

    public function store(Request $request)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;

        $validated = $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'required|string|max:2500',
            'external_link'         => 'nullable|url|max:255',
            'thumbnail_file'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'thumbnail_url_manual'  => 'nullable|url|max:255',

            // Siswa Utama (Pengaju / Leader)
            'primary_worker_id'     => 'nullable|uuid|exists:worker_profiles,id',
            'primary_name'          => 'nullable|string|max:255',
            'primary_class'         => 'nullable|string|max:100',
            'primary_role'          => 'nullable|string|max:100',
            'primary_linkedin'      => 'nullable|url|max:255',
            'primary_social'        => 'nullable|url|max:255',

            // Anggota tim tambahan (opsional)
            'contributors'                      => 'nullable|array|max:10',
            'contributors.*.worker_profile_id'  => 'nullable|uuid|exists:worker_profiles,id',
            'contributors.*.guest_name'         => 'nullable|string|max:100',
            'contributors.*.class_name'         => 'nullable|string|max:100',
            'contributors.*.role'               => 'nullable|string|max:100',
            'contributors.*.linkedin'           => 'nullable|url|max:255',
            'contributors.*.social'             => 'nullable|url|max:255',
        ]);

        // Thumbnail file atau URL manual
        $thumbnailUrl = null;
        if ($request->hasFile('thumbnail_file')) {
            $path = \App\Services\ImageOptimizerService::optimizeAndStore($request->file('thumbnail_file'), 'portfolios', 1280, 1280, 82);
            $thumbnailUrl = '/storage/' . $path;
        } elseif (!empty($validated['thumbnail_url_manual'])) {
            $thumbnailUrl = $validated['thumbnail_url_manual'];
        }

        // Cari worker_profile_id pada unit ini saja, jangan kaitkan ke siswa unit lain
        $primaryWorkerId = $validated['primary_worker_id'] ?? null;
        if (!$primaryWorkerId) {
            $anchorWorker = WorkerProfile::where('tefa_unit_id', $tefaUnitId)->first();
            $primaryWorkerId = $anchorWorker?->id;
        }

        $portfolio = Portfolio::create([
            'id'                => (string) Str::uuid(),
            'worker_profile_id' => $primaryWorkerId,
            'tefa_unit_id'      => $tefaUnitId,
            'title'             => $validated['title'],
            'description'       => $validated['description'],
            'external_link'     => $validated['external_link'] ?? null,
            'thumbnail_url'     => $thumbnailUrl,
            'status'            => PortfolioStatus::Approved, // Langsung disetujui karena diinput Admin
            'reviewed_by'       => $request->user()->id,
            'reviewed_at'       => now(),
        ]);

        // 1. Simpan Siswa Utama ke daftar kontributor
        $primaryDisplayName = !empty($validated['primary_worker_id']) ? null : ($validated['primary_name'] ?? 'Siswa Pengembang');
        PortfolioContributor::create([
            'id'                => (string) Str::uuid(),
            'portfolio_id'      => $portfolio->id,
            'worker_profile_id' => $validated['primary_worker_id'] ?? null,
            'guest_name'        => $primaryDisplayName,
            'role'              => PortfolioContributor::formatRolePayload(
                $validated['primary_role'] ?? 'Ketua Tim / Pengembang Utama',
                $validated['primary_linkedin'] ?? null,
                $validated['primary_social'] ?? null,
                $validated['primary_class'] ?? null
            ),
        ]);

        // 2. Simpan Anggota Tim Tambahan
        if (!empty($validated['contributors'])) {
            foreach ($validated['contributors'] as $item) {
                $workerProfileId = $item['worker_profile_id'] ?? null;
                $guestName       = $item['guest_name'] ?? null;

                if (!$workerProfileId && !$guestName) {
                    continue;
                }

                PortfolioContributor::create([
                    'id'                => (string) Str::uuid(),
                    'portfolio_id'      => $portfolio->id,
                    'worker_profile_id' => $workerProfileId ?: null,
                    'guest_name'        => $workerProfileId ? null : $guestName,
                    'role'              => PortfolioContributor::formatRolePayload(
                        $item['role'] ?? 'Kontributor Tim',
                        $item['linkedin'] ?? null,
                        $item['social'] ?? null,
                        $item['class_name'] ?? null
                    ),
                ]);
            }
        }

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio karya siswa berhasil ditambahkan dan langsung dipublikasikan!');
    }

    public function show(Request $request, Portfolio $portfolio)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($portfolio->tefa_unit_id !== $tefaUnitId, 403, 'Akses ditolak.');

        $portfolio->load(['worker.user', 'reviewer', 'contributors.workerProfile.user']);

        return view('admin.portfolios.show', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($portfolio->tefa_unit_id !== $tefaUnitId, 403, 'Akses ditolak.');

        // 1. Jika ini request update data karya dari modal edit
        if ($request->has('title')) {
            $validated = $request->validate([
                'title'                 => 'required|string|max:255',
                'description'           => 'required|string|max:2500',
                'external_link'         => 'nullable|url|max:255',
                'thumbnail_file'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'thumbnail_url_manual'  => 'nullable|url|max:255',

                // Siswa Utama
                'primary_worker_id'     => 'nullable|uuid|exists:worker_profiles,id',
                'primary_name'          => 'nullable|string|max:255',
                'primary_class'         => 'nullable|string|max:100',
                'primary_role'          => 'nullable|string|max:100',
                'primary_linkedin'      => 'nullable|url|max:255',
                'primary_social'        => 'nullable|url|max:255',

                // Anggota tim tambahan
                'contributors'                      => 'nullable|array|max:10',
                'contributors.*.worker_profile_id'  => 'nullable|uuid|exists:worker_profiles,id',
                'contributors.*.guest_name'         => 'nullable|string|max:100',
                'contributors.*.class_name'         => 'nullable|string|max:100',
                'contributors.*.role'               => 'nullable|string|max:100',
                'contributors.*.linkedin'           => 'nullable|url|max:255',
                'contributors.*.social'             => 'nullable|url|max:255',
            ]);

            $updateData = [
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'external_link' => $validated['external_link'] ?? null,
            ];

            if ($request->hasFile('thumbnail_file')) {
                $path = \App\Services\ImageOptimizerService::optimizeAndStore($request->file('thumbnail_file'), 'portfolios', 1280, 1280, 82);
                $updateData['thumbnail_url'] = '/storage/' . $path;
            } elseif (!empty($validated['thumbnail_url_manual'])) {
                $updateData['thumbnail_url'] = $validated['thumbnail_url_manual'];
            }

            if (!empty($validated['primary_worker_id'])) {
                $updateData['worker_profile_id'] = $validated['primary_worker_id'];
            }

            $portfolio->update($updateData);

            // Re-sync kontributor
            $portfolio->contributors()->delete();

            // Simpan Siswa Utama
            $primaryDisplayName = !empty($validated['primary_worker_id']) ? null : ($validated['primary_name'] ?? 'Siswa Pengembang');
            PortfolioContributor::create([
                'id'                => (string) Str::uuid(),
                'portfolio_id'      => $portfolio->id,
                'worker_profile_id' => $validated['primary_worker_id'] ?? null,
                'guest_name'        => $primaryDisplayName,
                'role'              => PortfolioContributor::formatRolePayload(
                    $validated['primary_role'] ?? 'Ketua Tim / Pengembang Utama',
                    $validated['primary_linkedin'] ?? null,
                    $validated['primary_social'] ?? null,
                    $validated['primary_class'] ?? null
                ),
            ]);

            // Simpan Anggota Tim Tambahan
            if (!empty($validated['contributors'])) {
                foreach ($validated['contributors'] as $item) {
                    $workerProfileId = $item['worker_profile_id'] ?? null;
                    $guestName       = $item['guest_name'] ?? null;

                    if (!$workerProfileId && !$guestName) {
                        continue;
                    }

                    PortfolioContributor::create([
                        'id'                => (string) Str::uuid(),
                        'portfolio_id'      => $portfolio->id,
                        'worker_profile_id' => $workerProfileId ?: null,
                        'guest_name'        => $workerProfileId ? null : $guestName,
                        'role'              => PortfolioContributor::formatRolePayload(
                            $item['role'] ?? 'Kontributor Tim',
                            $item['linkedin'] ?? null,
                            $item['social'] ?? null,
                            $item['class_name'] ?? null
                        ),
                    ]);
                }
            }

            return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio karya berhasil diperbarui!');
        }

        // 2. Jika ini review status dari show.blade.php
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $portfolio->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $msg = $validated['status'] === 'approved' 
            ? 'Portofolio berhasil di-ACC dan dipublikasikan.' 
            : 'Portofolio dikembalikan ke siswa untuk perbaikan.';

        return redirect()->route('admin.portfolios.index')->with('success', $msg);
    }

    public function destroy(Request $request, Portfolio $portfolio)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($portfolio->tefa_unit_id !== $tefaUnitId, 403, 'Akses ditolak.');

        if ($portfolio->thumbnail_url) {
            $path = ltrim(str_replace('/storage/', '', (string) $portfolio->thumbnail_url), '/');
            if ($path && !str_starts_with((string) $portfolio->thumbnail_url, 'http')) {
                Storage::disk('public')->delete($path);
            }
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portofolio berhasil dihapus dari sistem.');
    }
}
