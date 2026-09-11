@extends('layouts.public')

@section('content')

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-slate-50 px-4 py-12">

        <div class="w-full max-w-md">

            <!-- Card Login -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">

                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">
                        Tefa <span class="text-indigo-600">Hub</span>
                    </h2>

                    <h1 class="text-xl font-bold text-slate-900 mt-4">
                        Masuk ke Akun
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Silakan masuk untuk melanjutkan
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}">

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
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition duration-200"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />
                    </div>

                    <!-- Password -->
                    <div class="mt-5">

                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                            Kata Sandi
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition duration-200"
                        />

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2"
                        />

                    </div>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))

                        <div class="flex justify-end mt-3">

                            <a
                                href="{{ route('password.request') }}"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition duration-150"
                            >
                                Lupa password?
                            </a>

                        </div>

                    @endif

                    <!-- Tombol Login -->
                    <div class="mt-6">

                        <button
                            type="submit"
                            class="w-full justify-center rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 py-3.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/20 transition duration-200"
                        >
                            Masuk
                        </button>

                    </div>

                </form>

                <!-- Register -->
                <div class="mt-6 text-center">

                    <p class="text-sm text-slate-500">
                        Belum punya akun?

                        <a
                            href="{{ route('register') }}"
                            class="font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150 ml-1"
                        >
                            Daftar sekarang
                        </a>
                    </p>

                </div>

            </div>

            <!-- Keterangan -->
            <p class="mt-6 text-center text-xs text-slate-400">
                © {{ date('Y') }} TefaHub. Semua hak dilindungi.
            </p>

        </div>

    </div>

@endsection