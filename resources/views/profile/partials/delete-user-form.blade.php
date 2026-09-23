<section class="space-y-4">
    <header>
        <h2 class="text-base font-bold text-rose-700">
            Hapus Akun
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Setelah akun Anda dihapus, semua data dan informasi yang terkait akan dihapus secara permanen.
        </p>
    </header>

    <div>
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs rounded-xl transition"
        >
            Hapus Akun Saya
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
            </p>

            <div class="mt-4">
                <label for="password" class="sr-only">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-xl border-slate-200 text-sm focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Masukkan Kata Sandi Anda"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
