<section>
    <header>
        <h2 class="text-base font-bold text-slate-900">
            Informasi Profil
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Perbarui nama lengkap dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
            <input id="name" name="name" type="text" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor Telephone</label>
            <input id="phone" name="phone" type="text" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('phone', $user->phone) }}" required autofocus autocomplete="phone" />
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
            <input id="email" name="email" type="email" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-xs">
                    <p class="text-amber-700">
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline text-indigo-600 hover:text-indigo-800 font-semibold">
                            Klik di sini untuk kirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-emerald-600">
                            Tautan verifikasi baru telah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-bold text-emerald-600 flex items-center gap-1"
                >✓ Berhasil disimpan</p>
            @endif
        </div>
    </form>
</section>
