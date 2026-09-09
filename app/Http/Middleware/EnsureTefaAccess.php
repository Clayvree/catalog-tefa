<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTefaAccess
{
    /**
     * Pastikan admin_jurusan dan worker hanya bisa akses
     * resource yang memiliki tefa_unit_id sesuai unit mereka.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // SuperAdmin bisa akses semua
        if ($user->role === UserRole::SuperAdmin) {
            return $next($request);
        }

        // Admin Jurusan: periksa via pivot table
        if ($user->role === UserRole::AdminJurusan) {
            $tefaUnitId = $request->route('tefaUnit') ?? $request->input('tefa_unit_id');

            if ($tefaUnitId && ! $user->managedUnits()->where('tefa_units.id', $tefaUnitId)->exists()) {
                abort(403, 'Anda tidak memiliki akses ke unit TEFA ini.');
            }

            return $next($request);
        }

        // Worker: periksa via workerProfile
        if ($user->role === UserRole::Worker) {
            $workerProfile = $user->workerProfile;

            if (! $workerProfile) {
                abort(403, 'Profil worker tidak ditemukan.');
            }

            // Simpan tefa_unit_id di request agar controller bisa pakai
            $request->merge(['_worker_tefa_unit_id' => $workerProfile->tefa_unit_id]);

            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}
