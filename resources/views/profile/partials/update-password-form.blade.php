<section>
    <header>
        <h2 class="text-base font-bold text-slate-900">
            Ubah Kata Sandi
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Gunakan kombinasi password yang kuat untuk menjaga keamanan akun Anda.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-bold text-emerald-600 flex items-center gap-1"
                >✓ Kata sandi berhasil diperbarui</p>
            @endif
        </div>
    </form>
</section>
