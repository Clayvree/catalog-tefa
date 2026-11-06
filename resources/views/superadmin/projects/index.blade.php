<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Enterprise Monitoring</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Riwayat Proyek Seluruh Unit TEFA
                </h2>
            </div>
            <span class="text-xs font-bold text-slate-500">
                Laporan global pesanan & status pengerjaan industri.
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Global Metrics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Nilai Proyek</span>
                    <p class="text-xl sm:text-2xl font-black text-indigo-600">Rp{{ number_format((float)$stats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-[10px] text-slate-400">Akumulasi seluruh unit</p>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Pesanan</span>
                    <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
                    <p class="text-[10px] text-slate-400">Pesanan tercatat</p>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Sedang Berjalan</span>
                    <p class="text-2xl font-black text-emerald-600">{{ $stats['active'] }}</p>
                    <p class="text-[10px] text-slate-400">In Progress / Active</p>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Proyek Tuntas</span>
                    <p class="text-2xl font-black text-slate-900">{{ $stats['completed'] }}</p>
                    <p class="text-[10px] text-emerald-600 font-bold">100% Delivered</p>
                </div>
            </div>

            <!-- Project List Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="font-black text-base text-slate-900">Daftar Seluruh Proyek Teaching Factory</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Filter berdasarkan unit jurusan atau status pengerjaan.</p>
                        </div>
                        <span class="text-xs font-bold text-slate-400 shrink-0">{{ $projects->total() }} Proyek Tercatat</span>
                    </div>

                    <!-- Filter Bar -->
                    <form action="{{ route('superadmin.projects.index') }}" method="GET" class="flex flex-wrap items-center gap-3 pt-1">
                        <select name="unit" class="rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold py-2.5 pl-3.5 pr-8 text-slate-700 shadow-sm transition cursor-pointer">
                            <option value="">-- Semua Unit TEFA --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>

                        <select name="status" class="rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 text-xs font-bold py-2.5 pl-3.5 pr-8 text-slate-700 shadow-sm transition cursor-pointer">
                            <option value="">-- Semua Status --</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>

                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / klien..." class="w-48 sm:w-64 rounded-xl border border-slate-200 text-xs py-2.5 px-3.5 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 text-slate-800 placeholder:text-slate-400 shadow-sm transition">

                        <button type="submit" class="whitespace-nowrap px-4 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Filter</span>
                        </button>

                        @if(request()->hasAny(['unit', 'status', 'q']))
                            <a href="{{ route('superadmin.projects.index') }}" class="whitespace-nowrap px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5 whitespace-nowrap">Judul Proyek & Klien</th>
                                <th class="px-6 py-3.5 whitespace-nowrap">Unit TEFA</th>
                                <th class="px-6 py-3.5 whitespace-nowrap">Nilai Transaksi</th>
                                <th class="px-6 py-3.5 text-center whitespace-nowrap">Sub-Tugas</th>
                                <th class="px-6 py-3.5 whitespace-nowrap">Status</th>
                                <th class="px-6 py-3.5 text-right whitespace-nowrap">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($projects as $project)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="space-y-0.5">
                                            <p class="font-bold text-slate-900 text-sm">{{ $project->title }}</p>
                                            <p class="text-[10px] text-slate-400">Klien: <strong class="text-slate-600">{{ $project->client_name }}</strong></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px]">
                                             {{ $project->tefaUnit->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-900 text-sm">
                                        @if($project->final_price)
                                            Rp{{ number_format((float)$project->final_price, 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400 italic text-xs font-normal">Belum diset</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800">
                                        {{ $project->tasks->where('status', 'done')->count() }} / {{ $project->tasks->count() }} Selesai
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($project->status->value === 'completed')
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px] uppercase">✓ Completed</span>
                                        @elseif($project->status->value === 'in_progress')
                                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-extrabold text-[10px] uppercase">In Progress</span>
                                        @elseif($project->status->value === 'review')
                                            <span class="px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 font-extrabold text-[10px] uppercase">Review</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-extrabold text-[10px] uppercase">{{ $project->status->value }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-400 font-mono text-[10px]">
                                        {{ $project->created_at?->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data proyek yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $projects->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>