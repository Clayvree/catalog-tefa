<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Enums\PortfolioStatus;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        
        $status = $request->query('status', 'all');

        $query = Portfolio::with(['worker.user'])
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

        return view('admin.portfolios.index', compact('portfolios', 'status', 'stats'));
    }

    public function show(Request $request, Portfolio $portfolio)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($portfolio->tefa_unit_id !== $tefaUnitId, 403, 'Akses ditolak.');

        $portfolio->load(['worker.user', 'reviewer']);

        return view('admin.portfolios.show', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $tefaUnitId = $request->user()->managedUnits()->first()->id;
        abort_if($portfolio->tefa_unit_id !== $tefaUnitId, 403, 'Akses ditolak.');

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

        if ($portfolio->thumbnail_url && Storage::disk('public')->exists($portfolio->thumbnail_url)) {
            Storage::disk('public')->delete($portfolio->thumbnail_url);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portofolio berhasil dihapus dari sistem.');
    }
}
