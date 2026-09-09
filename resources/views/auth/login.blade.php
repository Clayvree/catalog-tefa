@extends('layouts.public')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

            <!-- Header Login (Logo & Judul) -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-3">
                    
                    <span class="text-2xl font-black tracking-tight text-slate-900">
                        Tefa <span class="text-indigo-600">Hub</span>
                    </span>
                </a>
                <h2 class="text-xl font-bold text-slate-800">Selamat Datang Kembali</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun TefaHub kamu</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status 
                class="mb-4" 
                :status="session('status')" 
            />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        Alamat Email
                    </label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150 text-sm placeholder-slate-400"
                        placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-slate-700">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline" href="{{ route('password.request') }}">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150 text-sm"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0 transition">
                        <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button type="submit" 
                        class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-100 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition duration-200 text-sm">
                        Masuk
                    </button>
                </div>

            </form>

            <!-- Link ke Halaman Register (Ditambahkan di sini) -->
            <div class="mt-6 text-center text-sm text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                    Daftar Mitra / Akun
                </a>
            </div>

        </div>

    </div>

@endsection