<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Delegasi AI via WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="waImportAI()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success from Session (e.g., job dispatched) -->
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kiri: Upload Box -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 relative">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Upload Chat Ekspor (.txt)</h3>
                        <p class="text-sm text-gray-500 mb-6">Sistem AI (Gemini) akan membaca percakapan Anda dengan klien, merangkum proyek, harga, dan memecahnya menjadi tugas spesifik untuk siswa.</p>
                        
                        <form action="{{ route('admin.projects.import-wa.store') }}" method="POST" enctype="multipart/form-data" @submit="isUploading = true">
                            @csrf
                            <input type="hidden" name="tefa_unit_id" value="{{ $tefaUnitId }}">
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 transition-colors bg-gray-50 mb-6 relative group">
                                <input type="file" name="chat_file" accept=".txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0].name">
                                
                                <div class="text-gray-500 group-hover:text-indigo-600 transition-colors">
                                    <svg class="mx-auto h-12 w-12 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-sm font-medium" x-show="!fileName">Klik atau seret file .txt ke sini</p>
                                    <p class="text-sm font-bold text-indigo-600" x-show="fileName" x-text="fileName"></p>
                                </div>
                            </div>
                            @error('chat_file')
                                <p class="text-red-500 text-xs mt-1 mb-4">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2" :disabled="!fileName || isUploading">
                                <span x-show="!isUploading">Mulai Ekstraksi AI</span>
                                <span x-show="isUploading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memproses...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Kanan: Preview Hasil AI (Interactive Confirmation Modal Logic in Page) -->
                <!-- Untuk mock, saya tampilkan form yang seharusnya di-fill otomatis oleh event broadcasting/polling setelah job selesai. 
                     Karena ini UI, kita buat agar admin bisa edit manual jika AI salah (sesuai prompt). -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 relative">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                ?? Hasil Konfirmasi AI
                            </h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full">Menunggu Review Anda</span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-6">Silakan periksa dan koreksi hasil ekstraksi AI di bawah ini sebelum mendelegasikan tugas ke siswa.</p>

                        <form action="{{ route('admin.projects.import-wa.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tefa_unit_id" value="{{ $tefaUnitId }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Klien</label>
                                    <input type="text" name="client_name" value="Bpk. Budi (Contoh)" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Kontak Klien</label>
                                    <input type="text" name="client_contact" value="0812345678" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Judul Proyek</label>
                                <input type="text" name="project_title" value="Desain Logo & Banner Sosial Media" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-bold">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Harga Kesepakatan Akhir (Rp)</label>
                                <input type="number" name="agreed_price" value="500000" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-bold text-green-600 bg-green-50">
                                <p class="text-xs text-gray-500 mt-1">AI mendeteksi harga ini disepakati di akhir chat.</p>
                            </div>

                            <div class="mb-8">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Ringkasan Proyek</label>
                                <textarea name="project_summary" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">Klien meminta 1 master logo dan 3 variasi banner instagram untuk campaign diskon bulan depan. Warna dominan biru.</textarea>
                            </div>

                            <!-- Tasks Sub-section -->
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center justify-between">
                                    <span>Delegasi Tugas (Dipecah oleh AI)</span>
                                    <button type="button" class="text-xs bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-medium">+ Tambah Manual</button>
                                </h4>

                                <!-- Task 1 -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-4 relative">
                                    <div class="absolute -left-2 -top-2 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow">1</div>
                                    <input type="text" name="tasks[0][title]" value="Desain Master Logo" class="w-full border-0 border-b border-dashed border-gray-300 focus:ring-0 focus:border-indigo-500 font-bold text-gray-800 mb-2 p-0 pb-1" placeholder="Judul Tugas">
                                    <textarea name="tasks[0][instructions]" rows="2" class="w-full border-0 focus:ring-0 p-0 text-sm text-gray-600 mb-2" placeholder="Instruksi spesifik...">Buat 2 alternatif logo dengan warna dominan biru muda.</textarea>
                                    <div class="flex items-center gap-2 bg-indigo-50 px-3 py-2 rounded text-xs">
                                        <span class="font-bold text-indigo-700">Alasan AI:</span>
                                        <span class="text-indigo-600">Pekerjaan desain grafis vektor dasar.</span>
                                        <input type="hidden" name="tasks[0][reasoning]" value="Pekerjaan desain grafis vektor dasar.">
                                    </div>
                                </div>

                                <!-- Task 2 -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm relative">
                                    <div class="absolute -left-2 -top-2 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow">2</div>
                                    <input type="text" name="tasks[1][title]" value="Desain 3 Banner IG" class="w-full border-0 border-b border-dashed border-gray-300 focus:ring-0 focus:border-indigo-500 font-bold text-gray-800 mb-2 p-0 pb-1" placeholder="Judul Tugas">
                                    <textarea name="tasks[1][instructions]" rows="2" class="w-full border-0 focus:ring-0 p-0 text-sm text-gray-600 mb-2" placeholder="Instruksi spesifik...">Setelah logo jadi, aplikasikan ke 3 template banner promo IG feed 1080x1080.</textarea>
                                    <div class="flex items-center gap-2 bg-indigo-50 px-3 py-2 rounded text-xs">
                                        <span class="font-bold text-indigo-700">Alasan AI:</span>
                                        <span class="text-indigo-600">Pekerjaan layouting socmed.</span>
                                        <input type="hidden" name="tasks[1][reasoning]" value="Pekerjaan layouting socmed.">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                                <button type="button" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">Batalkan</button>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                    Simpan & Delegasikan ke Siswa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('waImportAI', () => ({
        fileName: null,
        isUploading: false
    }))
})
</script>
