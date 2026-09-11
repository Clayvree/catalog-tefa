@extends('layouts.public')

@section('content')

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-slate-50 px-4 py-12">

        <div class="w-full max-w-md">

            <!-- Card Login -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">

                <!-- Header -->
                <div class="text-center mb-8">

                    <h1 class="text-2xl font-bold text-slate-800">
                        Masuk ke TefaHub
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
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
                        <x-input-label
                            for="email"
                            :value="__('Email')"
                            class="text-sm font-medium text-slate-700"
                        />

                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan email kamu"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />
                    </div>

                    <!-- Password -->
                    <div class="mt-5">

                        <x-input-label
                            for="password"
                            :value="__('Password')"
                            class="text-sm font-medium text-slate-700"
                        />

                        <x-text-input
                            id="password"
                            class="block mt-2 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password kamu"
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
                                class="text-sm text-indigo-600 hover:text-indigo-800"
                            >
                                Lupa password?
                            </a>

                        </div>

                    @endif

                    <!-- Tombol Login -->
                    <div class="mt-6">

                        <x-primary-button
                            class="w-full justify-center rounded-xl bg-indigo-600 py-3 text-sm font-semibold hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800"
                        >
                            {{ __('Log in') }}
                        </x-primary-button>

                    </div>

                </form>

                <!-- Register -->
                <div class="mt-6 text-center">

                    <p class="text-sm text-slate-500">
                        Belum punya akun?

                        <a
                            href="{{ route('register') }}"
                            class="font-semibold text-indigo-600 hover:text-indigo-800"
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