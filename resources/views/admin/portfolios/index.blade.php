<x-app-layout>
    <div x-data="{ 
        addModalOpen: false,
        editModalOpen: false,
        primaryUseDropdown: false,
        contributors: [],
        editForm: {
            id: '',
            title: '',
            description: '',
            external_link: '',
            thumbnail_url: '',
            primary_worker_id: '',
            primary_name: '',
            primary_class: '',
            primary_useDropdown: false,
            primary_role: '',
            primary_linkedin: '',
            primary_social: '',
            contributors: [],
            actionUrl: ''
        },
        addContributor() {
            this.contributors.push({ worker_profile_id: '', guest_name: '', class_name: '', role: '', linkedin: '', social: '', useDropdown: false });
        },
        removeContributor(idx) {
            this.contributors.splice(idx, 1);
        },
        addEditContributor() {
            this.editForm.contributors.push({ worker_profile_id: '', guest_name: '', class_name: '', role: '', linkedin: '', social: '', useDropdown: false });
        },
        removeEditContributor(idx) {
            this.editForm.contributors.splice(idx, 1);
        },
        openEdit(p) {
            this.editForm.id = p.id;
            this.editForm.title = p.title || '';
            this.editForm.description = p.description || '';
            this.editForm.external_link = p.external_link || '';
            this.editForm.thumbnail_url = p.thumbnail_url || '';
            this.editForm.actionUrl = '/admin/portfolios/' + p.id;

            let first = (p.contributors && p.contributors.length > 0) ? p.contributors[0] : null;
            if (first) {
                this.editForm.primary_worker_id = first.worker_profile_id || '';
                this.editForm.primary_name = first.guest_name || (first.worker_profile ? (first.worker_profile.user ? first.worker_profile.user.name : '') : '');
                this.editForm.primary_class = first.class_name || (first.worker_profile ? first.worker_profile.class_name : '') || '';
                this.editForm.primary_useDropdown = Boolean(first.worker_profile_id);
                this.editForm.primary_role = first.role_title || '';
                this.editForm.primary_linkedin = first.linkedin_url || '';
                this.editForm.primary_social = first.social_url || '';
            } else {
                this.editForm.primary_worker_id = p.worker_profile_id || '';
                this.editForm.primary_name = (p.worker && p.worker.user) ? p.worker.user.name : '';
                this.editForm.primary_class = p.worker ? (p.worker.class_name || '') : '';
                this.editForm.primary_useDropdown = Boolean(p.worker_profile_id);
                this.editForm.primary_role = '';
                this.editForm.primary_linkedin = '';
                this.editForm.primary_social = '';
            }

            this.editForm.contributors = [];
            if (p.contributors && p.contributors.length > 1) {
                for (let i = 1; i < p.contributors.length; i++) {
                    let c = p.contributors[i];
                    this.editForm.contributors.push({
                        worker_profile_id: c.worker_profile_id || '',
                        guest_name: c.guest_name || (c.worker_profile ? (c.worker_profile.user ? c.worker_profile.user.name : '') : ''),
                        class_name: c.class_name || (c.worker_profile ? c.worker_profile.class_name : '') || '',
                        role: c.role_title || '',
                        linkedin: c.linkedin_url || '',
                        social: c.social_url || '',
                        useDropdown: Boolean(c.worker_profile_id)
                    });
                }
            }

            this.editModalOpen = true;
        }
    }">

        <!-- Custom In-Scope Header (Alpine Reactive) -->
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Manajemen Karya Unit TEFA</span>
                    <h2 class="font-black text-xl sm:text-2xl text-slate-800 leading-tight mt-0.5">
                        Kelola Portofolio Siswa
                    </h2>
                </div>
                <button type="button" @click="addModalOpen = true" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Portofolio Baru</span>
                </button>
            </div>
        </div>

        <div class="py-8 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                            {{ session('success') }}
                        </span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold flex items-center justify-between shadow-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg>
                            {{ session('error') }}
                        </span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                        <p class="font-extrabold text-sm">Gagal Menyimpan Portofolio:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Statistics & Filters -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                            <p class="text-xs font-bold text-red-500 uppercase tracking-wider mb-1">Ditolak / Revisi</p>
                            <p class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</p>
                        </div>
                    </a>
                </div>

                <!-- Portfolios List -->
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-100">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($portfolios as $portfolio)
                                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col bg-slate-50 transition hover:shadow-md">
                                    <div class="h-48 bg-slate-200 relative group">
                                        @if($portfolio->thumbnail_url)
                                            <img src="{{ $portfolio->thumbnail_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80';">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-xs">
                                                Tidak ada gambar mockup
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
                                                $firstContrib = $portfolio->contributors->first();
                                                $creatorName = $firstContrib ? $firstContrib->display_name : ($portfolio->worker->user->name ?? 'Siswa Pengembang');
                                            @endphp
                                            <!-- Avatar Stack -->
                                            <div class="flex items-center">
                                                @php $avatarColors2 = ['bg-indigo-100 text-indigo-700','bg-violet-100 text-violet-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700']; @endphp
                                                @if($firstContrib && $firstContrib->avatar_url)
                                                    <div class="w-8 h-8 rounded-full ring-2 ring-white overflow-hidden flex-shrink-0">
                                                        <img src="{{ $firstContrib->avatar_url }}" class="w-full h-full object-cover">
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-full ring-2 ring-white bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs uppercase overflow-hidden flex-shrink-0">
                                                        {{ substr($creatorName, 0, 1) }}
                                                    </div>
                                                @endif

                                                @foreach($portfolio->contributors->skip(1)->take(3) as $ci => $contrib)
                                                    <div class="w-8 h-8 rounded-full ring-2 ring-white {{ $avatarColors2[$ci % 4] }} flex items-center justify-center font-bold text-xs overflow-hidden flex-shrink-0 -ml-2" title="{{ $contrib->display_name }}">
                                                        @if($contrib->avatar_url)
                                                            <img src="{{ $contrib->avatar_url }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ substr($contrib->display_name, 0, 1) }}
                                                        @endif
                                                    </div>
                                                @endforeach
                                                @if($portfolio->contributors->count() > 4)
                                                    <div class="w-8 h-8 rounded-full ring-2 ring-white bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 -ml-2">
                                                        +{{ $portfolio->contributors->count() - 4 }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-xs min-w-0">
                                                <p class="font-bold text-slate-800 truncate">{{ $creatorName }}</p>
                                                @if($portfolio->contributors->count() > 1)
                                                    <p class="text-slate-400 text-[10px]">+ {{ $portfolio->contributors->count() - 1 }} anggota tim</p>
                                                @else
                                                    <p class="text-slate-400 text-[10px]">{{ $portfolio->created_at->format('d M Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <h3 class="font-black text-sm text-slate-900 mb-1 line-clamp-1">{{ $portfolio->title }}</h3>
                                        <p class="text-xs text-slate-500 line-clamp-2 mb-4 flex-1">{{ $portfolio->description }}</p>

                                        <div class="grid grid-cols-3 gap-2 mt-auto pt-4 border-t border-slate-200">
                                            <a href="{{ route('admin.portfolios.show', $portfolio->id) }}" class="text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2 px-2 rounded-xl transition shadow-sm" title="Review atau lihat detail">
                                                Detail
                                            </a>
                                            <button type="button" @click="openEdit({{ json_encode($portfolio) }})" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs py-2 px-2 rounded-xl transition cursor-pointer text-center" title="Edit Portofolio">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.portfolios.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Hapus karya portofolio ini permanen?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs py-2 px-2 rounded-xl transition cursor-pointer" title="Hapus">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="m9 13.5-1.5 7L12 18l4.5 2.5-1.5-7"/></svg>
                                    </div>
                                    <h3 class="text-slate-800 font-black text-base mb-1">Belum Ada Portofolio</h3>
                                    <p class="text-xs text-slate-500 max-w-sm mb-4">Belum ada data portofolio siswa yang terdaftar pada filter status ini.</p>
                                    <button type="button" @click="addModalOpen = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow transition cursor-pointer">
                                        + Tambah Portofolio Pertama
                                    </button>
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

        <!-- Add Portfolio Modal (Admin/Guru) -->
        <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">Katalog & Showcase TEFA</span>
                            <h3 class="font-black text-base sm:text-lg text-slate-900 mt-0.5">Tambah Portofolio Karya Siswa</h3>
                            <p class="text-xs text-slate-500">Portofolio yang diinput guru langsung berstatus disetujui (tayang publik).</p>
                        </div>
                        <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                    </div>

                    <form action="{{ route('admin.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        {{-- Judul & Deskripsi --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Karya / Proyek <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required placeholder="Contoh: Platform E-Commerce UMKM / Desain 3D Booth" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Karya <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="3" required placeholder="Jelaskan gambaran umum proyek, tantangan teknis, teknologi yang digunakan, serta hasil karya..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Demo / GitHub / Web (Opsional)</label>
                                    <input type="url" name="external_link" placeholder="https://..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Upload Gambar / Mockup Proyek</label>
                                    <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                </div>
                            </div>
                        </div>

                        {{-- Section Siswa Utama (Pengaju/Ketua Tim) - DEFAULT MANUAL INPUT --}}
                        <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="block text-xs font-black uppercase text-indigo-700 tracking-wider">Siswa Pengerja Utama / Ketua Tim</span>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Langsung ketik nama siswa (tidak wajib punya akun/login TEFA)</p>
                                </div>
                                <button type="button" @click="primaryUseDropdown = !primaryUseDropdown" class="text-[10px] font-bold text-indigo-600 hover:underline cursor-pointer">
                                    <span x-text="primaryUseDropdown ? '✏️ Ketik nama manual' : '🔗 Pilih akun TEFA'"></span>
                                </button>
                            </div>
                            
                            <!-- Input Manual (Default) -->
                            <div x-show="!primaryUseDropdown" class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                                        <input type="text" name="primary_name" placeholder="Contoh: Budi Prasetyo" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Kelas / Jurusan (Opsional)</label>
                                        <input type="text" name="primary_class" placeholder="cth: XII PPLG 1" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                    </div>
                                </div>
                            </div>

                            <!-- Pilihan dari Akun Terdaftar (Opsional) -->
                            <div x-show="primaryUseDropdown" class="space-y-1">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Pilih dari Akun Siswa Terdaftar</label>
                                <select name="primary_worker_id" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($unitWorkers as $uw)
                                        <option value="{{ $uw->id }}">{{ $uw->user->name ?? '-' }} ({{ $uw->class_name ?? 'Kelas?' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Peran di Proyek</label>
                                <input type="text" name="primary_role" placeholder="cth: Project Leader & Fullstack Dev" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">LinkedIn URL (Opsional)</label>
                                    <input type="url" name="primary_linkedin" placeholder="https://linkedin.com/in/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Medsos / GitHub (Opsional)</label>
                                    <input type="url" name="primary_social" placeholder="https://instagram.com/... atau https://github.com/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        {{-- Section Anggota Tim Tambahan (DEFAULT MANUAL INPUT) --}}
                        <div class="space-y-3 pt-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-700 uppercase">Anggota Tim Tambahan (Opsional)</span>
                                    <p class="text-[10px] text-slate-400">Tambahkan siswa lain yang ikut mengerjakan proyek</p>
                                </div>
                                <button type="button" @click="addContributor()" class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-xs font-bold text-indigo-700 transition cursor-pointer flex items-center gap-1">
                                    <span>+ Tambah Anggota</span>
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(item, idx) in contributors" :key="idx">
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2.5 relative">
                                        <button type="button" @click="removeContributor(idx)" class="absolute top-2.5 right-3 text-slate-400 hover:text-red-500 text-base font-bold cursor-pointer" title="Hapus anggota">×</button>

                                        <!-- Manual Input (Default) -->
                                        <div x-show="!item.useDropdown" class="space-y-2">
                                            <div class="flex items-center justify-between pr-6">
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">Data Siswa Kontributor</span>
                                                <button type="button" @click="item.useDropdown = true; item.guest_name = ''" class="text-[10px] text-indigo-600 hover:underline cursor-pointer">
                                                    Pilih dari akun terdaftar →
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                <div class="sm:col-span-2">
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Siswa</label>
                                                    <input type="text" :name="`contributors[${idx}][guest_name]`" x-model="item.guest_name" placeholder="Ketik nama lengkap..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kelas</label>
                                                    <input type="text" :name="`contributors[${idx}][class_name]`" x-model="item.class_name" placeholder="cth: XII PPLG 2" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dropdown Input (Alternatif) -->
                                        <div x-show="item.useDropdown" class="space-y-1">
                                            <div class="flex items-center justify-between pr-6">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Siswa Terdaftar</label>
                                                <button type="button" @click="item.worker_profile_id = ''; item.useDropdown = false; item.guest_name = ''" class="text-[10px] text-indigo-600 hover:underline cursor-pointer">
                                                    Ketik nama manual →
                                                </button>
                                            </div>
                                            <select :name="`contributors[${idx}][worker_profile_id]`" x-model="item.worker_profile_id" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                <option value="">-- Pilih siswa --</option>
                                                @foreach($unitWorkers as $uw)
                                                    <option value="{{ $uw->id }}">{{ $uw->user->name ?? '-' }} ({{ $uw->class_name ?? 'Kelas?' }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Peran di Proyek</label>
                                            <input type="text" :name="`contributors[${idx}][role]`" x-model="item.role" placeholder="cth: UI Designer, Backend Dev, Video Editor" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">LinkedIn (Opsional)</label>
                                                <input type="url" :name="`contributors[${idx}][linkedin]`" x-model="item.linkedin" placeholder="https://linkedin.com/in/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Medsos / GitHub (Opsional)</label>
                                                <input type="url" :name="`contributors[${idx}][social]`" x-model="item.social" placeholder="https://instagram.com/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Simpan & Publikasikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Portfolio Modal (Admin/Guru) -->
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">Edit Karya Siswa</span>
                            <h3 class="font-black text-base sm:text-lg text-slate-900 mt-0.5">Perbarui Informasi Portofolio</h3>
                            <p class="text-xs text-slate-500">Edit data proyek, gambar mockup, dan profil siswa pengerja.</p>
                        </div>
                        <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">×</button>
                    </div>

                    <form :action="editForm.actionUrl" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Judul & Deskripsi --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Karya / Proyek <span class="text-red-500">*</span></label>
                                <input type="text" name="title" x-model="editForm.title" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Karya <span class="text-red-500">*</span></label>
                                <textarea name="description" x-model="editForm.description" rows="3" required class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Demo / GitHub / Web</label>
                                    <input type="url" name="external_link" x-model="editForm.external_link" placeholder="https://..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Mockup (Opsional)</label>
                                    <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                </div>
                            </div>
                        </div>

                        {{-- Section Siswa Utama (Pengaju/Ketua Tim) - DEFAULT MANUAL INPUT --}}
                        <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="block text-xs font-black uppercase text-indigo-700 tracking-wider">Siswa Pengerja Utama / Ketua Tim</span>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Ketik nama manual atau hubungkan ke akun TEFA</p>
                                </div>
                                <button type="button" @click="editForm.primary_useDropdown = !editForm.primary_useDropdown" class="text-[10px] font-bold text-indigo-600 hover:underline cursor-pointer">
                                    <span x-text="editForm.primary_useDropdown ? '✏️ Ketik nama manual' : '🔗 Pilih akun TEFA'"></span>
                                </button>
                            </div>
                            
                            <!-- Input Manual -->
                            <div x-show="!editForm.primary_useDropdown" class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Nama Lengkap Siswa</label>
                                        <input type="text" name="primary_name" x-model="editForm.primary_name" placeholder="Ketik nama siswa..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Kelas</label>
                                        <input type="text" name="primary_class" x-model="editForm.primary_class" placeholder="cth: XII PPLG 1" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                    </div>
                                </div>
                            </div>

                            <!-- Dropdown Akun -->
                            <div x-show="editForm.primary_useDropdown" class="space-y-1">
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Pilih dari Akun Siswa Terdaftar</label>
                                <select name="primary_worker_id" x-model="editForm.primary_worker_id" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($unitWorkers as $uw)
                                        <option value="{{ $uw->id }}">{{ $uw->user->name ?? '-' }} ({{ $uw->class_name ?? 'Kelas?' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Peran di Proyek</label>
                                <input type="text" name="primary_role" x-model="editForm.primary_role" placeholder="cth: Project Leader & Fullstack Dev" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">LinkedIn URL (Opsional)</label>
                                    <input type="url" name="primary_linkedin" x-model="editForm.primary_linkedin" placeholder="https://linkedin.com/in/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-1">Medsos / GitHub (Opsional)</label>
                                    <input type="url" name="primary_social" x-model="editForm.primary_social" placeholder="https://instagram.com/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                </div>
                            </div>
                        </div>

                        {{-- Section Anggota Tim Tambahan --}}
                        <div class="space-y-3 pt-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-700 uppercase">Anggota Tim Tambahan (Opsional)</span>
                                    <p class="text-[10px] text-slate-400">Edit data anggota tim pengerja</p>
                                </div>
                                <button type="button" @click="addEditContributor()" class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-xs font-bold text-indigo-700 transition cursor-pointer flex items-center gap-1">
                                    <span>+ Tambah Anggota</span>
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(item, idx) in editForm.contributors" :key="idx">
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2.5 relative">
                                        <button type="button" @click="removeEditContributor(idx)" class="absolute top-2.5 right-3 text-slate-400 hover:text-red-500 text-base font-bold cursor-pointer" title="Hapus anggota">×</button>

                                        <!-- Manual Input (Default) -->
                                        <div x-show="!item.useDropdown" class="space-y-2">
                                            <div class="flex items-center justify-between pr-6">
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">Data Siswa Kontributor</span>
                                                <button type="button" @click="item.useDropdown = true; item.guest_name = ''" class="text-[10px] text-indigo-600 hover:underline cursor-pointer">
                                                    Pilih dari akun terdaftar →
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                <div class="sm:col-span-2">
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nama Siswa</label>
                                                    <input type="text" :name="`contributors[${idx}][guest_name]`" x-model="item.guest_name" placeholder="Ketik nama lengkap..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kelas</label>
                                                    <input type="text" :name="`contributors[${idx}][class_name]`" x-model="item.class_name" placeholder="cth: XII PPLG 2" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dropdown Input -->
                                        <div x-show="item.useDropdown" class="space-y-1">
                                            <div class="flex items-center justify-between pr-6">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Siswa Terdaftar</label>
                                                <button type="button" @click="item.worker_profile_id = ''; item.useDropdown = false; item.guest_name = ''" class="text-[10px] text-indigo-600 hover:underline cursor-pointer">
                                                    Ketik nama manual →
                                                </button>
                                            </div>
                                            <select :name="`contributors[${idx}][worker_profile_id]`" x-model="item.worker_profile_id" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                                <option value="">-- Pilih siswa --</option>
                                                @foreach($unitWorkers as $uw)
                                                    <option value="{{ $uw->id }}">{{ $uw->user->name ?? '-' }} ({{ $uw->class_name ?? 'Kelas?' }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Peran di Proyek</label>
                                            <input type="text" :name="`contributors[${idx}][role]`" x-model="item.role" placeholder="cth: UI Designer, Backend Dev" class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">LinkedIn (Opsional)</label>
                                                <input type="url" :name="`contributors[${idx}][linkedin]`" x-model="item.linkedin" placeholder="https://linkedin.com/in/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Medsos / GitHub (Opsional)</label>
                                                <input type="url" :name="`contributors[${idx}][social]`" x-model="item.social" placeholder="https://instagram.com/..." class="w-full rounded-xl border-slate-200 bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
