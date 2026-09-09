<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TefaUnit;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', UserRole::AdminJurusan)->with('managedUnits')->latest()->get();
        $units = TefaUnit::where('is_active', true)->get();
        return view('superadmin.admins.index', compact('admins', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'tefa_unit_id' => 'required|exists:tefa_units,id',
        ]);

        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::AdminJurusan,
            'email_verified_at' => now(),
        ]);

        $user->managedUnits()->sync([$validated['tefa_unit_id']]);

        return redirect()->back()->with('success', 'Akun Admin Jurusan berhasil dibuat dan dihubungkan ke Unit TEFA!');
    }

    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'tefa_unit_id' => 'required|exists:tefa_units,id',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }
        $admin->save();

        $admin->managedUnits()->sync([$validated['tefa_unit_id']]);

        return redirect()->back()->with('success', 'Data Admin Jurusan berhasil diperbarui!');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== UserRole::AdminJurusan) {
            return redirect()->back()->with('error', 'Hanya akun Admin Jurusan yang dapat dihapus.');
        }

        $admin->managedUnits()->detach();
        $admin->delete();

        return redirect()->back()->with('success', 'Akun Admin Jurusan berhasil dihapus.');
    }
}