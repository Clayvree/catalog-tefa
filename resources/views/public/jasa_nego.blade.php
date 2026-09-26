@extends('layouts.public')

@section('title', 'Konsultasi & Nego Jasa — ' . $item->title)

@section('content')
<div class="bg-gradient-to-tr from-slate-950 via-slate-900 to-indigo-950 py-12 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white mb-4 transition">
            ← Kembali ke Katalog
        </a>
        <div class="flex items-center gap-2 mb-2">
            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-extrabold text-[10px] uppercase border border-emerald-500/30">
                🛠️ Layanan Jasa & Kustom Proyek
            </span>
            <span class="text-xs text-slate-400">• Negosiasi & Penawaran Kustom</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black tracking-tight">{{ $item->title }}</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl">
            Layanan jasa ini dikerjakan langsung oleh talenta siswa vokasi berbakat di bawah bimbingan instruktur profesional unit {{ $item->tefaUnit->name ?? 'TEFA' }}.
        </p>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Nego Form (8 cols) -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-sm space-y-6">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-black text-lg text-slate-900">Form Pengajuan Kebutuhan & Nego Harga</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Sampaikan kebutuhan spesifik Anda untuk mendapatkan estimasi pengerjaan dan penawaran terbaik.</p>
                    </div>
                </div>

                <form action="{{ route('jasa.nego.submit', $item->slug) }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Nama Anda / Instansi</span>
                            <p class="font-bold text-slate-900 text-sm">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Nomor WhatsApp Aktif</span>
                            <p class="font-bold text-slate-900 text-sm">{{ auth()->user()->whatsapp_number ?? auth()->user()->phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rincian / Brief Kebutuhan Proyek</label>
                        <textarea name="project_brief" rows="4" required placeholder="Jelaskan apa yang ingin Anda buat, fitur yang dibutuhkan, bahan/materi yang sudah disiapkan, atau referensi desain..." class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium leading-relaxed"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ekspektasi Budget Anda (Rp) (Opsional)</label>
                            <input type="number" name="budget_expectation" placeholder="Contoh: {{ (int)$item->price }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                            <span class="text-[10px] text-slate-400">Harga acuan standar mulai dari: <strong>Rp{{ number_format((float)$item->price, 0, ',', '.') }}</strong></span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Tanggal Selesai (Deadline)</label>
                            <input type="date" name="target_deadline" class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-[11px] text-slate-400">
                            💡 Data pengajuan akan otomatis diteruskan ke WhatsApp Admin Jurusan untuk negosiasi langsung.
                        </p>

                        <button type="submit" class="px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
                            <span>💬 Kirim Kebutuhan & Nego via WhatsApp</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.203c.043.072.043.419-.101.824z"/></svg>
                        </button>
                    </div>
                </form>

            </div>

            <!-- Right Column: Jurusan Info Card (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Service Detail Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="aspect-video rounded-2xl bg-slate-100 overflow-hidden border border-slate-200">
                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-emerald-600 uppercase">{{ $item->category->name ?? 'Layanan Jasa' }}</span>
                        <h4 class="font-black text-sm text-slate-900">{{ $item->title }}</h4>
                        <div class="pt-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Acuan Tarif Mulai:</span>
                            <span class="text-lg font-black text-indigo-600">Rp{{ number_format((float)$item->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed pt-2 border-t border-slate-100">
                        {{ $item->description }}
                    </p>
                </div>

                <!-- Penanggung Jawab TEFA Unit -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 font-black text-lg flex items-center justify-center">
                            {{ substr($item->tefaUnit->name ?? 'T', 0, 1) }}
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Unit Pelaksana:</span>
                            <h4 class="font-black text-sm text-slate-900">{{ $item->tefaUnit->name }}</h4>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1">
                        <span class="font-bold text-slate-800">Penanggung Jawab:</span>
                        <p class="font-semibold text-indigo-600">{{ $admin->name ?? 'Admin Jurusan TEFA' }}</p>
                        <p class="text-[10px] text-slate-400">{{ $admin->email ?? 'admin@tefa.id' }}</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection