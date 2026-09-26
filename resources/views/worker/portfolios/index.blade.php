<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Student Showcase & Portfolios</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Ajukan & Kelola Portofolio Karya
                </h2>
            </div>
            <button @click="$dispatch('open-add-portfolio-modal')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>+ Ajukan Karya Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{ addModalOpen: false }" @open-add-portfolio-modal.window="addModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                    <p class="font-extrabold text-sm">Gagal Mengirim Portofolio:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Diajukan</span>
                    <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Disetujui & Tayang</span>
                    <p class="text-2xl font-black text-emerald-600">{{ $stats['approved'] }}</p>
                    <p class="text-[10px] text-emerald-600">Tampil di Web Publik</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-amber-500 uppercase">Menunggu Review</span>
                    <p class="text-2xl font-black text-amber-500">{{ $stats['pending'] }}</p>
                    <p class="text-[10px] text-slate-400">Ditinjau Admin</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Perlu Revisi</span>
                    <p class="text-2xl font-black text-slate-700">{{ $stats['rejected'] }}</p>
                </div>
            </div>

            <!-- Portfolio Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($portfolios as $portfolio)
                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        
                        <div>
                            <!-- Thumbnail -->
                            <div class="h-44 bg-slate-100 relative overflow-hidden">
                                <img src="{{ $portfolio->thumbnail_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover">
                                <div class="absolute top-2.5 right-2.5">
                                    @if($portfolio->status->value === 'approved')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500 text-white shadow-md">
                                            ✓ Tayang
                                        </span>
                                    @elseif($portfolio->status->value === 'pending')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amber-500 text-white shadow-md">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-red-500 text-white shadow-md">
                                            Revisi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-5 space-y-2">
                                <span class="text-[10px] font-bold text-slate-400">{{ $portfolio->created_at?->format('d M Y') }}</span>
                                <h4 class="font-black text-sm text-slate-900 leading-snug">{{ $portfolio->title }}</h4>
                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    {{ $portfolio->description }}
                                </p>
                                
                                @if($portfolio->review_notes)
                                    <div class="mt-3 p-3 rounded-xl border {{ $portfolio->status->value === 'approved' ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100' }}">
                                        <p class="text-[10px] font-bold {{ $portfolio->status->value === 'approved' ? 'text-emerald-700' : 'text-red-700' }} uppercase tracking-wider mb-1">
                                            📝 Catatan Guru ({{ $portfolio->reviewer->name ?? 'Admin' }})
                                        </p>
                                        <p class="text-xs {{ $portfolio->status->value === 'approved' ? 'text-emerald-600' : 'text-red-600' }} line-clamp-3 italic">
                                            "{{ $portfolio->review_notes }}"
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Footer -->
                        <div class="p-5 pt-0 space-y-3">
                            @if($portfolio->external_link)
                                <a href="{{ $portfolio->external_link }}" target="_blank" class="block text-center py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                    Lihat Link Demo &rarr;
                                </a>
                            @endif

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                <span class="text-[10px] text-slate-400">{{ $portfolio->tefaUnit->name ?? 'TEFA' }}</span>
                                <form action="{{ route('worker.portfolios.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Hapus pengajuan portofolio ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] font-bold text-red-600 hover:underline cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-16 px-4 bg-white rounded-3xl border border-dashed border-slate-300 text-center space-y-3">
                        <span class="text-4xl">🎨</span>
                        <h3 class="text-base font-black text-slate-800">Belum Ada Portofolio yang Diajukan</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Tunjukkan karya terbaik Anda kepada calon klien industri dengan mengajukan portofolio karya.
                        </p>
                        <button type="button" @click="addModalOpen = true" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md cursor-pointer">
                            + Ajukan Portofolio Sekarang
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $portfolios->links() }}
            </div>

        </div>

        <!-- Add Portfolio Modal -->
        <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">Student Showcase</span>
                            <h3 class="font-black text-base text-slate-900 mt-0.5">Ajukan Portofolio Karya Baru</h3>
                        </div>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                    </div>

                    <form action="{{ route('worker.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Karya / Proyek</label>
                            <input type="text" name="title" required placeholder="Contoh: Redesign Aplikasi Mobile Banking / Logo Brand Kopi" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Karya & Peran Anda</label>
                            <textarea name="description" rows="3" required placeholder="Jelaskan konsep karya, tools yang digunakan (Laravel, Figma, Illustrator), serta kontribusi Anda..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Demo / GitHub / Behance / Figma (Opsional)</label>
                            <input type="url" name="external_link" placeholder="https://github.com/... atau https://behance.net/..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Foto Mockup / Karya</label>
                            <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, atau WEBP. Maksimal 5 MB.</p>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>