<x-public-layout>
    <div class="min-h-screen bg-slate-50 text-slate-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Header Halaman -->
            <div class="text-center space-y-3">
                
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                    Ada Pertanyaan? Kami Siap <span class="text-indigo-600">Membantu.</span>
                </h1>
                <p class="text-slate-500 text-sm max-w-xl mx-auto">
                    Kirimkan pesan atau pertanyaan Anda mengenai produk dan layanan Teaching Factory. Tim kami akan segera merespons.
                </p>
            </div>

            <!-- Card Form Contact -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-xl shadow-slate-200/50">
                
                <!-- Notifikasi Sukses -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold rounded-2xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required placeholder="Masukkan nama Anda"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" name="email" id="email" required placeholder="nama@email.com"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition">
                        </div>
                    </div>

                    <!-- Subjek -->
                    <div>
                        <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subjek Pesan</label>
                        <input type="text" name="subject" id="subject" required placeholder="Topik atau judul pesan"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition">
                    </div>

                    <!-- Pesan -->
                    <div>
                        <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pesan Anda</label>
                        <textarea name="message" id="message" rows="5" required placeholder="Tuliskan pesan lengkap Anda di sini..."
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition"></textarea>
                    </div>

                    <!-- Tombol Submit -->
                    <div>
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 transition duration-200 cursor-pointer">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-public-layout>