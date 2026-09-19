@extends('layouts.public')

@section('content')

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-slate-50 px-4 py-12">

        <div class="w-full max-w-md">

            <!-- Card Lupa Password -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">

                <!-- Header -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">
                        Tefa <span class="text-indigo-600">Hub</span>
                    </h2>

                    <h1 class="text-xl font-bold text-slate-900 mt-4">
                        Lupa Kata Sandi?
                    </h1>

                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Masukkan alamat email kamu. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi kamu.
                    </p>
                </div>

                <!-- Session Status (Pesan Berhasil Kirim Email) -->
                <x-auth-session-status
                    class="mb-4 text-sm text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100 text-center"
                    :status="session('status')"
                />

                <!-- Form Lupa Password -->
                <form method="POST" action="{{ route('password.email') }}">

                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                            Alamat Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition duration-200"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />
                    </div>

                    <!-- Tombol Kirim Email -->
                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full justify-center rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 py-3.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/20 transition duration-200"
                        >
                            Kirim Tautan Reset Password
                        </button>
                    </div>

                </form>

                <!-- Kembal ke Login -->
                <div class="mt-6 text-center">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150"
                    >
                        &larr; Kembali ke halaman Masuk
                    </a>
                </div>

            </div>

            <!-- Keterangan -->
            <p class="mt-6 text-center text-xs text-slate-400">
                © {{ date('Y') }} TefaHub. Semua hak dilindungi.
            </p>

        </div>

    </div>

@endsection