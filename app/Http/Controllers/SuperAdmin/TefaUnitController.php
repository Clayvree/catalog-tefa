<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TefaUnit;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'banner_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'logo_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        TefaUnit::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'banner_url' => $request->hasFile('banner_file')
                ? '/storage/' . $request->file('banner_file')->store('units', 'public')
                : null,
            'logo_url' => $request->hasFile('logo_file')
                ? '/storage/' . $request->file('logo_file')->store('units', 'public')
                : null,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Unit TEFA berhasil ditambahkan!');
    }

    public function update(Request $request, TefaUnit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'logo_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? $unit->description,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ];

        foreach (['banner_file' => 'banner_url', 'logo_file' => 'logo_url'] as $fileKey => $column) {
            if ($request->hasFile($fileKey)) {
                $oldPath = ltrim(str_replace('/storage/', '', (string) $unit->{$column}), '/');
                if ($oldPath && !str_starts_with((string) $unit->{$column}, 'http')) {
                    Storage::disk('public')->delete($oldPath);
                }

                $data[$column] = '/storage/' . $request->file($fileKey)->store('units', 'public');
            }
        }

        $unit->update($data);

        return redirect()->back()->with('success', 'Unit TEFA berhasil diperbarui!');
    }

    public function destroy(TefaUnit $unit)
    {
        foreach ([$unit->banner_url, $unit->logo_url] as $image) {
            $path = ltrim(str_replace('/storage/', '', (string) $image), '/');
            if ($path && !str_starts_with((string) $image, 'http')) {
                Storage::disk('public')->delete($path);
            }
        }

        $unit->delete();
        return redirect()->back()->with('success', 'Unit TEFA berhasil dihapus!');
    }
}