<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Pengaturan Profil') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                {{ Auth::user()->role?->label() ?? 'Akun Publik' }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-[calc(100vh-140px)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Summary Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white font-bold text-xl flex items-center justify-center shadow-md shadow-indigo-600/20 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base font-bold text-slate-900 truncate">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Card 1: Update Information -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/70 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Card 2: Update Password -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/70 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Card 3: Delete Account (Subtle Danger Area) -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/70 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
