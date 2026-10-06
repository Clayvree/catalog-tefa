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

    <div class="py-8 bg-slate-50 min-h-screen" x-data="portfolioPage()" @open-add-portfolio-modal.window="addModalOpen = true">
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
                    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between"
                         x-data="{ editContribOpen: false }">
                        
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

                                <!-- Kontributor Avatar Stack -->
                                @if($portfolio->contributors->count() > 0)
                                    <div class="pt-2">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1.5">Tim / Kontributor</p>
                                        <div class="flex items-center gap-1.5">
                                            {{-- Avatar pengaju (diri sendiri) --}}
                                            <div title="{{ $workerProfile->user->name }} (Pengaju)" class="w-7 h-7 rounded-full ring-2 ring-white overflow-hidden bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-[10px] flex-shrink-0">
                                                @if($workerProfile->avatar_url)
                                                    <img src="{{ asset('storage/' . $workerProfile->avatar_url) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($workerProfile->user->name ?? 'S', 0, 1) }}
                                                @endif
                                            </div>
                                            {{-- Avatar kontributor lain --}}
                                            @foreach($portfolio->contributors->take(4) as $contributor)
                                                <div title="{{ $contributor->display_name }}{{ $contributor->role ? ' · ' . $contributor->role : '' }}"
                                                     class="w-7 h-7 rounded-full ring-2 ring-white overflow-hidden bg-slate-200 flex items-center justify-center text-slate-600 font-black text-[10px] flex-shrink-0 -ml-2">
                                                    @if($contributor->avatar_url)
                                                        <img src="{{ asset('storage/' . $contributor->avatar_url) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($contributor->display_name, 0, 1) }}
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($portfolio->contributors->count() > 4)
                                                <span class="text-[10px] font-bold text-slate-500 ml-1">+{{ $portfolio->contributors->count() - 4 }}</span>
                                            @endif
                                        </div>
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
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="editContribOpen = true" class="text-[11px] font-bold text-indigo-600 hover:underline cursor-pointer">
                                        👥 Tim
                                    </button>
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

                        <!-- Edit Kontributor Modal (per card) -->
                        <div x-show="editContribOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" @click="editContribOpen = false"></div>
                                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-5"
                                     x-data="contributorForm({{ json_encode($portfolio->contributors->map(fn($c) => ['worker_profile_id' => $c->worker_profile_id, 'guest_name' => $c->guest_name, 'role' => $c->role, 'display_name' => $c->display_name])->values()) }})">

                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div>
                                            <span class="text-[10px] font-bold text-indigo-600 uppercase">Kelola Tim</span>
                                            <h3 class="font-black text-base text-slate-900 mt-0.5">Kontributor Proyek</h3>
                                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $portfolio->title }}</p>
                                        </div>
                                        <button type="button" @click="editContribOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                                    </div>

                                    <form action="{{ route('worker.portfolios.contributors.update', $portfolio->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PATCH')

                                        <!-- Daftar Kontributor Dinamis -->
                                        <div class="space-y-3">
                                            <template x-for="(item, idx) in contributors" :key="idx">
                                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2.5 relative">
                                                    <button type="button" @click="remove(idx)" class="absolute top-2 right-2.5 text-slate-400 hover:text-red-500 text-sm font-bold cursor-pointer">×</button>

                                                    <!-- Pilih siswa (dengan akun) atau nama bebas -->
                                                    <div x-show="item.worker_profile_id !== null || item.useDropdown">
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Siswa (punya akun TEFA)</label>
                                                        <select :name="`contributors[${idx}][worker_profile_id]`" x-model="item.worker_profile_id"
                                                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                            <option value="">-- Pilih siswa --</option>
                                                            @foreach($unitWorkers as $uw)
                                                                <option value="{{ $uw->id }}">{{ $uw->user->name ?? '-' }} ({{ $uw->class_name ?? 'Kelas?' }})</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" @click="item.worker_profile_id = null; item.useDropdown = false; item.guest_name = ''" class="text-[10px] text-slate-400 hover:text-indigo-600 mt-1 cursor-pointer">
                                                            Ganti ke nama manual →
                                                        </button>
                                                    </div>

                                                    <div x-show="item.worker_profile_id === null && !item.useDropdown">
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama (tanpa akun TEFA)</label>
                                                        <input type="text" :name="`contributors[${idx}][guest_name]`" x-model="item.guest_name"
                                                               placeholder="Nama lengkap kontributor..."
                                                               class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                        <button type="button" @click="item.useDropdown = true; item.guest_name = ''" class="text-[10px] text-slate-400 hover:text-indigo-600 mt-1 cursor-pointer">
                                                            Pilih dari daftar siswa →
                                                        </button>
                                                    </div>

                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Peran / Role (opsional)</label>
                                                        <input type="text" :name="`contributors[${idx}][role]`" x-model="item.role"
                                                               placeholder="cth: UI Design, Backend, Ilustrasi..."
                                                               class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <button type="button" @click="addGuest()" class="w-full py-2 border-2 border-dashed border-slate-300 hover:border-indigo-400 text-slate-500 hover:text-indigo-600 rounded-2xl text-xs font-bold transition cursor-pointer">
                                            + Tambah Kontributor
                                        </button>

                                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                            <button type="button" @click="editContribOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Simpan Tim</button>
                                        </div>
                                    </form>
                                </div>
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
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8 space-y-6"
                     x-data="contributorForm([])">
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

                        <!-- Kontributor Tambahan -->
                        <div class="space-y-2.5 pt-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Tim / Kontributor Lain</label>
                                <span class="text-[10px] text-slate-400">Opsional</span>
                            </div>
                            <p class="text-[10px] text-slate-500">Tambahkan teman yang ikut mengerjakan proyek ini. Bisa siswa TEFA atau nama bebas.</p>

                            <div class="space-y-3">
                                <template x-for="(item, idx) in contributors" :key="idx">
                                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2.5 relative">
                                        <button type="button" @click="remove(idx)" class="absolute top-2 right-2.5 text-slate-400 hover:text-red-500 text-sm font-bold cursor-pointer">×</button>

                                        <div x-show="item.worker_profile_id !== null || item.useDropdown">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Siswa (punya akun TEFA)</label>
                                            <select :name="`contributors[${idx}][worker_profile_id]`" x-model="item.worker_profile_id"
                                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                <option value="">-- Pilih siswa --</option>
                                                @foreach($unitWorkers as $uw)
                                                    <option value="{{ $uw->id }}">{{ $uw->user->name ?? '-' }} ({{ $uw->class_name ?? 'Kelas?' }})</option>
                                                @endforeach
                                            </select>
                                            <button type="button" @click="item.worker_profile_id = null; item.useDropdown = false; item.guest_name = ''" class="text-[10px] text-slate-400 hover:text-indigo-600 mt-1 cursor-pointer">
                                                Ganti ke nama manual →
                                            </button>
                                        </div>

                                        <div x-show="item.worker_profile_id === null && !item.useDropdown">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama (tanpa akun TEFA)</label>
                                            <input type="text" :name="`contributors[${idx}][guest_name]`" x-model="item.guest_name"
                                                   placeholder="Nama lengkap kontributor..."
                                                   class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                            <button type="button" @click="item.useDropdown = true; item.guest_name = ''" class="text-[10px] text-slate-400 hover:text-indigo-600 mt-1 cursor-pointer">
                                                Pilih dari daftar siswa →
                                            </button>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Peran / Role (opsional)</label>
                                            <input type="text" :name="`contributors[${idx}][role]`" x-model="item.role"
                                                   placeholder="cth: UI Design, Backend, Ilustrasi..."
                                                   class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="addGuest()" class="w-full py-2 border-2 border-dashed border-slate-200 hover:border-indigo-400 text-slate-400 hover:text-indigo-600 rounded-2xl text-xs font-bold transition cursor-pointer">
                                + Tambah Kontributor
                            </button>
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

    @push('scripts')
    <script>
        function portfolioPage() {
            return {
                addModalOpen: false,
            };
        }

        function contributorForm(initial) {
            return {
                contributors: initial.map(c => ({
                    worker_profile_id: c.worker_profile_id || null,
                    guest_name: c.guest_name || '',
                    role: c.role || '',
                    useDropdown: !!c.worker_profile_id,
                })),
                addGuest() {
                    this.contributors.push({ worker_profile_id: null, guest_name: '', role: '', useDropdown: false });
                },
                remove(idx) {
                    this.contributors.splice(idx, 1);
                },
            };
        }
    </script>
    @endpush
</x-app-layout>