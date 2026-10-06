<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Kelola Portofolio Siswa') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center justify-between shadow-sm">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-bold flex items-center justify-between shadow-sm">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg>
                        {{ session('error') }}
                    </span>
                </div>
            @endif
            
            <!-- Statistics & Filters -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin.portfolios.index') }}" class="bg-white p-4 rounded-2xl shadow-sm border {{ $status === 'all' ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-slate-100' }} transition flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Semua</p>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['all'] }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.portfolios.index', ['status'=>'pending']) }}" class="bg-white p-4 rounded-2xl shadow-sm border {{ $status === 'pending' ? 'border-amber-500 ring-1 ring-amber-500' : 'border-slate-100' }} transition flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-1">Menunggu Review</p>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['pending'] }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.portfolios.index', ['status'=>'approved']) }}" class="bg-white p-4 rounded-2xl shadow-sm border {{ $status === 'approved' ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-100' }} transition flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-1">Disetujui (Tayang)</p>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['approved'] }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.portfolios.index', ['status'=>'rejected']) }}" class="bg-white p-4 rounded-2xl shadow-sm border {{ $status === 'rejected' ? 'border-red-500 ring-1 ring-red-500' : 'border-slate-100' }} transition flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-red-500 uppercase tracking-wider mb-1">Ditolak / Perlu Revisi</p>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</p>
                    </div>
                </a>
            </div>

            <!-- Portfolios List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($portfolios as $portfolio)
                            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col bg-slate-50 transition hover:shadow-md">
                                <div class="h-48 bg-slate-200 relative">
                                    @if($portfolio->thumbnail_url)
                                        <img src="{{ asset('storage/' . $portfolio->thumbnail_url) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            Tidak ada gambar
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2 flex flex-col items-end gap-1">
                                        @if($portfolio->status->value === 'approved')
                                            <span class="px-2.5 py-1 bg-emerald-500 text-white text-[10px] font-black tracking-wider uppercase rounded-full shadow-sm">TAYANG PUBLIK</span>
                                        @elseif($portfolio->status->value === 'pending')
                                            <span class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black tracking-wider uppercase rounded-full shadow-sm">BUTUH REVIEW</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-500 text-white text-[10px] font-black tracking-wider uppercase rounded-full shadow-sm">REVISI</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <div class="flex items-center gap-2 mb-3">
                                        @php
                                            $allNames = collect([$portfolio->worker->user->name ?? 'Anonim']);
                                            foreach ($portfolio->contributors as $c) { $allNames->push($c->display_name); }
                                        @endphp
                                        <!-- Avatar Stack -->
                                        <div class="flex items-center">
                                            @php $avatarColors2 = ['bg-indigo-100 text-indigo-700','bg-violet-100 text-violet-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700']; @endphp
                                            <div class="w-8 h-8 rounded-full ring-2 ring-white bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs uppercase overflow-hidden flex-shrink-0">
                                                @if($portfolio->worker->avatar_url)
                                                    <img src="{{ asset('storage/' . $portfolio->worker->avatar_url) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($portfolio->worker->user->name ?? 'U', 0, 1) }}
                                                @endif
                                            </div>
                                            @foreach($portfolio->contributors->take(3) as $ci => $contrib)
                                                <div class="w-8 h-8 rounded-full ring-2 ring-white {{ $avatarColors2[$ci % 4] }} flex items-center justify-center font-bold text-xs overflow-hidden flex-shrink-0 -ml-2" title="{{ $contrib->display_name }}">
                                                    @if($contrib->avatar_url)
                                                        <img src="{{ asset('storage/' . $contrib->avatar_url) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($contrib->display_name, 0, 1) }}
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($portfolio->contributors->count() > 3)
                                                <div class="w-8 h-8 rounded-full ring-2 ring-white bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 -ml-2">
                                                    +{{ $portfolio->contributors->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-xs min-w-0">
                                            <p class="font-bold text-slate-800 truncate">{{ $portfolio->worker->user->name ?? 'Siswa Anonim' }}</p>
                                            @if($portfolio->contributors->count() > 0)
                                                <p class="text-slate-400 text-[10px]">+ {{ $portfolio->contributors->count() }} kontributor</p>
                                            @else
                                                <p class="text-slate-500">{{ $portfolio->created_at->format('d M Y, H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <h3 class="font-black text-sm text-slate-900 mb-1 line-clamp-1">{{ $portfolio->title }}</h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 flex-1">{{ $portfolio->description }}</p>

                                    <div class="flex gap-2 mt-auto pt-4 border-t border-slate-200">
                                        <a href="{{ route('admin.portfolios.show', $portfolio->id) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2 px-4 rounded-xl transition shadow-sm">
                                            @if($portfolio->status->value === 'pending') Review Sekarang @else Lihat Detail @endif
                                        </a>
                                        <form action="{{ route('admin.portfolios.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Hapus portofolio ini permanen?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs py-2 px-3 rounded-xl transition" title="Hapus">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="m9 13.5-1.5 7L12 18l4.5 2.5-1.5-7"/></svg>
                                </div>
                                <h3 class="text-slate-800 font-bold mb-1">Belum Ada Portofolio</h3>
                                <p class="text-sm text-slate-500">Tidak ada data portofolio yang sesuai dengan filter.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $portfolios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>