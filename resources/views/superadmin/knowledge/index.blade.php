<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">SuperAdmin</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                    Kelola Konteks AI Global &amp; Per Jurusan
                </h2>
            </div>
            <button type="button" @click="addModalOpen = true"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>+ Tambah Konteks</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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
                    <li><span class="font-bold">Langkah 1 &mdash; Baca Judul:</span> AI membaca semua judul konteks untuk memutuskan topik mana yang relevan.</li>
                    <li><span class="font-bold">Langkah 2 &mdash; Baca Deskripsi:</span> Hanya deskripsi topik yang dipilih yang dibaca secara mendalam untuk membentuk jawaban.</li>
                </ol>
                <div class="mt-2 p-2.5 rounded-xl bg-indigo-100 border border-indigo-200 text-[10px] text-indigo-700 font-semibold space-y-0.5">
                    <p>&#x1F30D; <span class="font-bold">Konteks Global</span> (tefa_unit_id = null): Tersedia untuk semua jurusan, digunakan oleh seluruh AI chatbot.</p>
                    <p>&#x1F3EB; <span class="font-bold">Konteks Per Jurusan</span>: Hanya digunakan oleh AI chatbot jurusan yang bersangkutan.</p>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('superadmin.knowledge.index') }}"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition border {{ !request()->has('global') && !request()->has('unit') ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-400' }}">
                    Semua
                </a>
                <a href="{{ route('superadmin.knowledge.index', ['global' => 1]) }}"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition border {{ request()->has('global') ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-blue-400' }}">
                    &#x1F30D; Global (Semua Jurusan)
                </a>
                @foreach($units as $u)
                    <a href="{{ route('superadmin.knowledge.index', ['unit' => $u->id]) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition border {{ request('unit') == $u->id ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-400' }}">
                        {{ $u->name }}
                    </a>
                @endforeach
            </div>

            {{-- Knowledge Table --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-black text-base text-slate-900">Daftar Konteks AI</h3>
                    <span class="text-xs font-bold text-indigo-600">{{ $knowledges->total() }} Entri</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Judul &amp; Deskripsi</th>
                                <th class="px-6 py-3.5">Cakupan</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Diperbarui</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($knowledges as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4 max-w-sm">
                                        <p class="font-bold text-slate-900 text-sm">{{ $item->title }}</p>
                                        <p class="text-[11px] text-slate-500 leading-relaxed mt-0.5 line-clamp-2">{{ $item->description }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(is_null($item->tefa_unit_id))
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px] border border-blue-200">
                                                &#x1F30D; Global
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[10px] border border-indigo-200">
                                                &#x1F3EB; {{ $item->tefaUnit->name ?? 'Jurusan' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->is_active)
                                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                                                &#x25CF; Aktif
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-bold text-[10px] border border-slate-200">
                                                &#x25CB; Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-400 text-[11px]">
                                        {{ $item->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                @click="active = {
                                                    id: '{{ $item->id }}',
                                                    title: '{{ addslashes($item->title) }}',
                                                    description: '{{ addslashes($item->description) }}',
                                                    is_active: {{ $item->is_active ? 'true' : 'false' }},
                                                    unit_id: '{{ $item->tefa_unit_id ?? '' }}'
                                                }; editModalOpen = true"
                                                class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition cursor-pointer">
                                                Edit
                                            </button>
                                            <form action="{{ route('superadmin.knowledge.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus konteks AI ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold text-[11px] transition cursor-pointer">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <p class="text-2xl mb-2">&#x1F9E0;</p>
                                        <p class="font-bold">Belum ada konteks AI yang ditambahkan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($knowledges->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $knowledges->links() }}
                    </div>
                @endif
            </div>

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

                    <form action="{{ route('superadmin.knowledge.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Cakupan Jurusan
                            </label>
                            <select name="tefa_unit_id"
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="">&#x1F30D; Global (Semua Jurusan)</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-blue-500 font-medium mt-1">
                                Pilih jurusan atau biarkan Global agar tersedia untuk semua AI chatbot.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Judul Konteks <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" required
                                placeholder="Contoh: Visi Misi Sekolah, Program Beasiswa, Fasilitas Lab"
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
                            <input type="checkbox" name="is_active" id="sa_add_is_active" value="1" checked
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="sa_add_is_active" class="text-xs font-bold text-slate-700">
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

                    <form :action="`{{ url('superadmin/knowledge') }}/${active.id}`" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Cakupan Jurusan
                            </label>
                            <select name="tefa_unit_id"
                                class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-bold">
                                <option value="" :selected="active.unit_id === ''">&#x1F30D; Global (Semua Jurusan)</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" :selected="active.unit_id === '{{ $u->id }}'">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

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
                            <input type="checkbox" name="is_active" id="sa_edit_is_active" value="1"
                                :checked="active.is_active"
                                @change="active.is_active = $event.target.checked"
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="sa_edit_is_active" class="text-xs font-bold text-slate-700">
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
