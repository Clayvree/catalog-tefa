<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Analytics & Key Metrics</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Statistik & Ringkasan Data TEFA
                </h2>
            </div>
            <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                <a href="{{ route('superadmin.stats-report') }}" target="_blank" class="whitespace-nowrap inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Ekspor Statistik (PDF)
                </a>
                <a href="{{ route('superadmin.catalog-brochure') }}" class="whitespace-nowrap inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                    Cetak Brosur PDF
                </a>
                <span class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200">
                     Ketua TEFA Umum
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
                        <span class="text-lg"></span>
                    </div>
                    <p class="text-xl sm:text-2xl font-black text-slate-900">
                        Rp{{ number_format((float)$stats['total_revenue_est'], 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-emerald-600 font-bold">Dari seluruh pengerjaan proyek</p>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>UNIT JURUSAN</span>
                        <span class="text-lg"></span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-indigo-600">{{ $stats['total_units'] }}</p>
                    <p class="text-[11px] text-slate-500">Unit Teaching Factory</p>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>KATALOG PRODUK</span>
                        <span class="text-lg"></span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_products'] }}</p>
                    <p class="text-[11px] text-slate-500">Barang & Jasa Terdaftar</p>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-400">
                        <span>SISWA WORKER</span>
                        <span class="text-lg"></span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total_workers'] }}</p>
                    <p class="text-[11px] text-slate-500">Talenta Vokasi Aktif</p>
                </div>

            </div>

            <!-- 2. Interactive Realtime Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Chart 1: Revenue & Project Volume Trend -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-black text-base text-slate-900">Tren Pertumbuhan & Estimasi Nilai Proyek</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live Realtime
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Akumulasi nilai transaksi (Rp) dan jumlah proyek selama 6 bulan terakhir.</p>
                        </div>
                        <a href="{{ route('superadmin.stats-report') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                            <span>Unduh PDF</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    <div class="relative h-72 sm:h-80 w-full">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Status & Unit Proportion -->
                <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="font-black text-base text-slate-900">Distribusi Status Proyek</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Proporsi seluruh pesanan pengerjaan industri.</p>
                        </div>
                        <div class="relative h-60 sm:h-64 w-full flex items-center justify-center my-2">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-center">
                        <div class="p-2.5 rounded-2xl bg-indigo-50/70 border border-indigo-100">
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">Total Proyek</span>
                            <p class="text-lg font-black text-indigo-900 mt-0.5">{{ $stats['total_projects'] }}</p>
                        </div>
                        <div class="p-2.5 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                            <span class="text-[10px] font-bold text-emerald-600 uppercase">Total Unit</span>
                            <p class="text-lg font-black text-emerald-900 mt-0.5">{{ $stats['total_units'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Breakdown Unit TEFA Performance -->
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
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="font-black text-base text-slate-900">Pesanan Proyek Terbaru</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Proyek yang masuk via AI / Admin.</p>
                            </div>
                            <a href="{{ route('superadmin.projects.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                Lihat Semua &rarr;
                            </a>
                        </div>

                        <div class="space-y-3 pt-3">
                            @forelse($recentProjects as $project)
                                <div class="p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 transition space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm line-clamp-1">{{ $project->title }}</h4>
                                            <p class="text-[11px] text-slate-500 mt-0.5">Klien: <strong class="text-slate-700">{{ $project->client_name }}</strong></p>
                                        </div>
                                        @if($project->final_price)
                                            <span class="font-black text-xs text-indigo-600 shrink-0">Rp{{ number_format((float)$project->final_price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between pt-1 border-t border-slate-200/50 text-[10px]">
                                        <span class="inline-flex items-center gap-1 font-semibold text-slate-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            {{ $project->tefaUnit->name ?? 'Unit TEFA' }}
                                        </span>
                                        @php
                                            $statusBadge = match($project->status?->value ?? '') {
                                                'completed' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Completed'],
                                                'in_progress' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'In Progress'],
                                                'review' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Review'],
                                                default => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Aktif'],
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-md font-bold uppercase tracking-wider border {{ $statusBadge['bg'] }}">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <p class="text-xs italic">Belum ada proyek yang tercatat.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);

            // Chart 1: Revenue & Projects Trend
            const ctxTrend = document.getElementById('revenueTrendChart');
            if (ctxTrend) {
                new Chart(ctxTrend, {
                    type: 'bar',
                    data: {
                        labels: chartData.monthly.labels,
                        datasets: [
                            {
                                label: 'Estimasi Nilai Proyek (Rp)',
                                data: chartData.monthly.revenue,
                                backgroundColor: 'rgba(79, 70, 229, 0.85)',
                                borderColor: 'rgb(79, 70, 229)',
                                borderWidth: 1,
                                borderRadius: 8,
                                yAxisID: 'yRevenue',
                                order: 2
                            },
                            {
                                label: 'Jumlah Proyek Masuk',
                                data: chartData.monthly.projects,
                                type: 'line',
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderWidth: 3,
                                pointBackgroundColor: 'rgb(16, 185, 129)',
                                pointRadius: 4,
                                tension: 0.35,
                                fill: true,
                                yAxisID: 'yCount',
                                order: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    usePointStyle: true,
                                    font: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold', size: 11 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        if (context.dataset.yAxisID === 'yRevenue') {
                                            return ' ' + context.dataset.label + ': Rp' + new Intl.NumberFormat('id-ID').format(context.raw);
                                        }
                                        return ' ' + context.dataset.label + ': ' + context.raw + ' Proyek';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                            },
                            yRevenue: {
                                type: 'linear',
                                position: 'left',
                                grid: { color: 'rgba(226, 232, 240, 0.7)' },
                                ticks: {
                                    font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
                                    callback: function (value) {
                                        if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                        if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                        return value;
                                    }
                                }
                            },
                            yCount: {
                                type: 'linear',
                                position: 'right',
                                grid: { display: false },
                                ticks: {
                                    stepSize: 1,
                                    font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 }
                                }
                            }
                        }
                    }
                });
            }

            // Chart 2: Project Status Distribution
            const ctxStatus = document.getElementById('projectStatusChart');
            if (ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.status.labels,
                        datasets: [{
                            data: chartData.status.counts,
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.85)',
                                'rgba(148, 163, 184, 0.85)',
                                'rgba(16, 185, 129, 0.85)',
                                'rgba(239, 68, 68, 0.85)'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true,
                                    font: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold', size: 11 }
                                }
                            }
                        },
                        cutout: '68%'
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>