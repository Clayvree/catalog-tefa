<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.portfolios.index') }}" class="text-slate-400 hover:text-slate-600 transition">
                &larr; Kembali
            </a>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Review Portofolio Siswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: Portfolio Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 mb-2">{{ $portfolio->title }}</h1>
                            <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                                <span class="flex items-center gap-1">?? Diajukan: {{ $portfolio->created_at->format('d M Y, H:i') }}</span>
                                @if($portfolio->external_link)
                                    <a href="{{ $portfolio->external_link }}" target="_blank" class="text-indigo-600 hover:underline flex items-center gap-1">
                                        ?? Link Eksternal
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($portfolio->status->value === 'approved')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-black tracking-wider uppercase rounded-full">Disetujui</span>
                            @elseif($portfolio->status->value === 'pending')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-black tracking-wider uppercase rounded-full">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-black tracking-wider uppercase rounded-full">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    @if($portfolio->thumbnail_url)
                        <div class="w-full aspect-video bg-slate-100 rounded-2xl overflow-hidden mb-6">
                            <img src="{{ asset('storage/' . $portfolio->thumbnail_url) }}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Deskripsi Proyek:</h3>
                        <div class="text-slate-600 text-sm whitespace-pre-wrap leading-relaxed">{{ $portfolio->description }}</div>
                    </div>
                </div>
            </div>

            <!-- Right: Action & Teacher Review -->
            <div class="space-y-6">
                <!-- Worker Info -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Informasi Siswa</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 overflow-hidden">
                            @if($portfolio->worker->avatar_url)
                                <img src="{{ asset('storage/' . $portfolio->worker->avatar_url) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-indigo-700 font-black">{{ substr($portfolio->worker->user->name ?? 'S', 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $portfolio->worker->user->name ?? 'Siswa Anonim' }}</p>
                            <p class="text-xs text-slate-500">Divisi/Keahlian: {{ $portfolio->worker->skills->pluck('name')->join(', ') ?: 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Review Form / Output -->
                <div class="bg-slate-900 p-6 rounded-3xl shadow-lg border border-slate-800 text-white">
                    <h3 class="text-sm font-black tracking-wide mb-4">Keputusan Admin / Guru</h3>
                    
                    <form action="{{ route('admin.portfolios.update', $portfolio->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-400 mb-2">Pilih Status Portofolio</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="approved" class="peer sr-only" {{ old('status', $portfolio->status->value) === 'approved' ? 'checked' : '' }}>
                                    <div class="text-center py-2 px-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-300 text-xs font-bold peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500 peer-checked:text-emerald-400 hover:bg-slate-700 transition">
                                        ACC & Publikasi
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="rejected" class="peer sr-only" {{ old('status', $portfolio->status->value) === 'rejected' ? 'checked' : '' }}>
                                    <div class="text-center py-2 px-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-300 text-xs font-bold peer-checked:bg-red-500/20 peer-checked:border-red-500 peer-checked:text-red-400 hover:bg-slate-700 transition">
                                        Tolak / Revisi
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 mb-2">Catatan / Rekomendasi (Wajib untuk Publik / Revisi)</label>
                            <textarea name="review_notes" rows="4" class="w-full bg-slate-800 border-slate-700 text-white rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-500" placeholder="Berikan komentar, validasi skill, atau alasan perbaikan...">{{ old('review_notes', $portfolio->review_notes) }}</textarea>
                            <p class="text-[10px] text-slate-500 mt-1">Catatan ini akan tampil di web publik sebagai bukti validasi/sertifikasi oleh guru.</p>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30">
                            Simpan Keputusan
                        </button>
                    </form>

                    @if($portfolio->reviewed_by)
                        <div class="mt-6 pt-6 border-t border-slate-800">
                            <p class="text-xs text-slate-400 mb-1">Terakhir direview oleh:</p>
                            <p class="text-sm font-bold text-slate-200">{{ $portfolio->reviewer->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-500">{{ $portfolio->reviewed_at?->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
