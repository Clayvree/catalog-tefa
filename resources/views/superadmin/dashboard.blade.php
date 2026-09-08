<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Analytics & Key Metrics</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Statistik & Ringkasan Data TEFA
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200">
                    👑 Ketua TEFA Umum
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- 1. Stats Counter Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>TOTAL OMSET EST.</span>
                        <span class="text-lg">💰</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-black text-slate-900">
                        Rp{{ number_format((float)$stats['total_revenue_est'], 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-emerald-600 font-bold">Dari seluruh pengerjaan proyek</p>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>UNIT JURUSAN</span>
                        <span class="text-lg">🏫</span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-indigo-600">{{ $stats['total_units'] }}</p>
                    <p class="text-[11px] text-slate-500">Unit Teaching Factory</p>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>KATALOG PRODUK</span>
                        <span class="text-lg">📦</span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_products'] }}</p>
                    <p class="text-[11px] text-slate-500">Barang & Jasa Terdaftar</p>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>SISWA WORKER</span>
                        <span class="text-lg">🎓</span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_workers'] }}</p>
                    <p class="text-[11px] text-slate-500">Talenta Vokasi Aktif</p>
                </div>

            </div>

            <!-- 2. Breakdown Unit TEFA Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-base text-slate-900">Performa Unit Teaching Factory</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Distribusi katalog dan keterlibatan siswa per jurusan.</p>
                        </div>
                        <a href="{{ route('superadmin.units.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                            Kelola Unit &rarr;
                        </a>
                    </div>

                    <div class="space-y-4">
                        @foreach($topUnits as $unit)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 text-sm">{{ $unit->name }}</span>
                                    </div>
                                    <span class="font-bold text-indigo-600">{{ $unit->catalog_items_count }} Katalog • {{ $unit->worker_profiles_count }} Siswa</span>
                                </div>
                                <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min(100, max(15, $unit->catalog_items_count * 15)) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Projects Activity -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-base text-slate-900">Pesanan Proyek Terbaru</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Proyek yang masuk via AI / Admin.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentProjects as $project)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900 line-clamp-1">{{ $project->title }}</span>
                                    @if($project->final_price)
                                        <span class="font-black text-indigo-600">Rp{{ number_format((float)$project->final_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1">
                                    <span>🏫 {{ $project->tefaUnit->name ?? 'Unit TEFA' }}</span>
                                    <span>Klien: {{ $project->client_name }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-8">Belum ada proyek yang tercatat.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>