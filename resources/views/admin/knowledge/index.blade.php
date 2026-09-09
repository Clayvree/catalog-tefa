<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ $unit->name }}</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Konteks AI Chat
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Pengetahuan ini digunakan AI chatbot untuk menjawab pertanyaan pengunjung tentang jurusan Anda.
                </p>
            </div>
            <button type="button" @click="addModalOpen = true"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>+ Tambah Konteks</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        addModalOpen: false,
        editModalOpen: false,
        active: { id: '', title: '', description: '', is_active: true }
    }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-sm">
                    <span>&#x2713; {{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold space-y-1 shadow-sm">
                    <p class="font-extrabold text-sm">Gagal Menyimpan:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px] text-red-700 font-normal">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info Banner --}}
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs leading-relaxed shadow-sm">
                <p class="font-extrabold text-sm mb-1">&#x1F916; Cara Kerja AI Dua Langkah</p>
                <ol class="list-decimal list-inside space-y-1 text-[11px] font-medium text-indigo-700">
                    <li><span class="font-bold">Langkah 1 &mdash; Baca Judul:</span> AI membaca semua <span class="font-bold">judul</span> konteks untuk memutuskan topik mana yang relevan dengan pertanyaan pengunjung.</li>
                    <li><span class="font-bold">Langkah 2 &mdash; Baca Deskripsi:</span> Hanya deskripsi dari topik yang dipilih yang dibaca secara mendalam untuk membentuk jawaban.</li>
                </ol>
                <p class="mt-2 text-[10px] text-indigo-500 font-semibold">
                    &#x1F4A1; Tips: Buat judul yang jelas dan deskriptif agar AI bisa memilih konteks yang tepat.
                </p>
            </div>

            {{-- Knowledge List --}}
            <div class="space-y-3">
                @forelse($knowledges as $item)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <h3 class="font-black text-sm text-slate-900">{{ $item->title }}</h3>
                                @if($item->is_active)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                                        &#x25CF; Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-bold text-[10px] border border-slate-200">
                                        &#x25CB; Nonaktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">{{ $item->description }}</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-2">
                                Diperbarui {{ $item->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button"
                                @click="active = {
                                    id: '{{ $item->id }}',
                                    title: '{{ addslashes($item->title) }}',
                                    description: '{{ addslashes($item->description) }}',
                                    is_active: {{ $item->is_active ? 'true' : 'false' }}
                                }; editModalOpen = true"
                                class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition cursor-pointer">
                                Edit
                            </button>
                            <form action="{{ route('admin.knowledge.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus konteks AI ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold text-[11px] transition cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                        <p class="text-3xl mb-3">&#x1F9E0;</p>
                        <p class="text-sm font-bold text-slate-500">Belum ada konteks AI untuk jurusan ini.</p>
                        <p class="text-xs text-slate-400 mt-1">Tambah konteks agar AI chatbot bisa menjawab pertanyaan tentang jurusan Anda.</p>
                        <button type="button" @click="addModalOpen = true"
                            class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition cursor-pointer">
                            + Tambah Konteks Pertama
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($knowledges->hasPages())
                <div class="pt-2">
                    {{ $knowledges->links() }}
                </div>
            @endif

        </div>

        {{-- Add Modal --}}
        <div x-show="addModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>

                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full p-6 sm:p-8 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Tambah Konteks AI Baru</h3>
                        <button type="button" @click="addModalOpen = false"
                            class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
                    </div>

                    <form action="{{ route('admin.knowledge.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Judul Konteks <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" required
                                placeholder="Contoh: Program Unggulan RPL, Biaya Pendaftaran, Prestasi Siswa"
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            <p class="text-[10px] text-indigo-500 font-medium mt-1">
                                &#x1F4A1; AI membaca judul ini untuk memutuskan kapan harus menggunakan konteks ini. Buat sejelas mungkin.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Deskripsi / Isi Konteks <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" rows="5" required
                                placeholder="Tuliskan informasi detail yang ingin disampaikan AI kepada pengunjung tentang topik ini..."
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="add_is_active" value="1" checked
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="add_is_active" class="text-xs font-bold text-slate-700">
                                Aktifkan konteks ini (AI akan menggunakannya)
                            </label>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="addModalOpen = false"
                                class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer transition">
                                Simpan Konteks
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="editModalOpen" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>

                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full p-6 sm:p-8 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-black text-base text-slate-900">Edit Konteks AI</h3>
                        <button type="button" @click="editModalOpen = false"
                            class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
                    </div>

                    <form :action="`{{ url('admin/knowledge') }}/${active.id}`" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Judul Konteks <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" x-model="active.title" required
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            <p class="text-[10px] text-indigo-500 font-medium mt-1">
                                &#x1F4A1; AI membaca judul ini untuk memutuskan kapan harus menggunakan konteks ini.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Deskripsi / Isi Konteks <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" rows="5" x-model="active.description" required
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                :checked="active.is_active"
                                @change="active.is_active = $event.target.checked"
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="edit_is_active" class="text-xs font-bold text-slate-700">
                                Aktifkan konteks ini (AI akan menggunakannya)
                            </label>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false"
                                class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold cursor-pointer hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md cursor-pointer transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
