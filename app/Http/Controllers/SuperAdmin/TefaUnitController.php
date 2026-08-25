<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TefaUnit;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TefaUnitController extends Controller
{
    public function index()
    {
        $units = TefaUnit::with(['admins', 'catalogItems', 'workerProfiles.user'])->latest()->get();
        $admins = User::where('role', UserRole::AdminJurusan)->get();
        return view('superadmin.units.index', compact('units', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tefa_units,slug',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        TefaUnit::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'banner_url' => $validated['banner_url'] ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&q=80',
            'logo_url' => $validated['logo_url'] ?? null,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Unit TEFA berhasil ditambahkan!');
    }

    public function update(Request $request, TefaUnit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $unit->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? $unit->description,
            'banner_url' => $validated['banner_url'] ?? $unit->banner_url,
            'logo_url' => $validated['logo_url'] ?? $unit->logo_url,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Unit TEFA berhasil diperbarui!');
    }

    public function destroy(TefaUnit $unit)
    {
        $unit->delete();
        return redirect()->back()->with('success', 'Unit TEFA berhasil dihapus!');
    }
}