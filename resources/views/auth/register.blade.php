@extends('layouts.public')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

            <!-- Header Register (Logo & Judul) -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-3">
                    
                    <span class="text-2xl font-black tracking-tight text-slate-900">
                        Tefa <span class="text-indigo-600">Hub</span>
                    </span>
                </a>
                <h2 class="text-xl font-bold text-slate-800">Buat Akun Baru</h2>
                <p class="text-sm text-slate-500 mt-1">Lengkapi data di bawah untuk mendaftar</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150 text-sm placeholder-slate-400"
                        placeholder="Nama lengkap kamu">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        Alamat Email
                    </label>
                    <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150 text-sm placeholder-slate-400"
                        placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                        Kata Sandi
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150 text-sm placeholder-slate-400"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                        Konfirmasi Kata Sandi
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition duration-150 text-sm placeholder-slate-400"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-100 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition duration-200 text-sm">
                        Daftar Akun
                    </button>
                </div>

            </form>

            <!-- Link ke Halaman Login -->
            <div class="mt-6 text-center text-sm text-slate-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                    Masuk di sini
                </a>
            </div>

        </div>

    </div>

@endsection