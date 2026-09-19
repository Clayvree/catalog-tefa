<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Delegasi AI via WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="waImportAI()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
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

                <!-- Kanan: List Draft AI -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 relative">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                Riwayat Import WhatsApp
                            </h3>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($drafts as $d)
                            <div class="border rounded-xl p-4 flex items-center justify-between {{ $d->status === 'ready' ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200 bg-white' }}">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">
                                        {{ Str::limit(basename($d->chat_file_path), 30) }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">Diunggah: {{ $d->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div>
                                    @if($d->status === 'ready')
                                        <a href="{{ route('admin.projects.import-wa.review', $d->id) }}" class="inline-block px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-lg shadow hover:bg-indigo-700">Review Hasil AI</a>
                                    @elseif($d->status === 'confirmed')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 font-bold text-xs rounded-full">Terkonfirmasi</span>
                                    @elseif($d->status === 'failed')
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 font-bold text-xs rounded-full">Gagal Ekstraksi</span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 font-bold text-xs rounded-full">Memproses</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 text-sm">
                                Belum ada riwayat import WhatsApp.
                            </div>
                            @endforelse
                        </div>
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
