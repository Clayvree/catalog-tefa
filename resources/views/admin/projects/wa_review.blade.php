<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.projects.import-wa.create') }}" class="text-gray-500 hover:text-gray-700">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Hasil Ekstraksi AI') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="waReview()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 sm:p-8">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        ?? Konfirmasi & Edit Hasil AI
                    </h3>
                </div>
                
                <p class="text-sm text-gray-500 mb-6">Silakan periksa dan koreksi hasil ekstraksi AI di bawah ini sebelum mendelegasikan tugas ke siswa.</p>

                <form action="{{ route('admin.projects.import-wa.confirm', $draft->id) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Klien</label>
                            <input type="text" name="client_name" value="{{ old('client_name', $draft->ai_result['client_name'] ?? '') }}" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kontak Klien</label>
                            <input type="text" name="client_contact" value="{{ old('client_contact', $draft->ai_result['client_contact'] ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Judul Proyek</label>
                        <input type="text" name="project_title" value="{{ old('project_title', $draft->ai_result['project_title'] ?? '') }}" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-bold">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Harga Kesepakatan Akhir (Rp)</label>
                        <input type="number" name="agreed_price" value="{{ old('agreed_price', $draft->ai_result['agreed_price'] ?? 0) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-bold text-green-600 bg-green-50">
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ringkasan Proyek</label>
                        <textarea name="project_summary" rows="3" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('project_summary', $draft->ai_result['project_summary'] ?? '') }}</textarea>
                    </div>

                    <!-- Tasks Sub-section -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center justify-between">
                            <span>Delegasi Tugas & Tim (Dipecah oleh AI)</span>
                            <button type="button" @click="addTask()" class="text-xs bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-medium">+ Tambah Manual</button>
                        </h4>

                        <template x-for="(task, index) in tasks" :key="index">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6 relative">
                                <div class="absolute -left-2 -top-2 w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow" x-text="index + 1"></div>
                                <button type="button" @click="removeTask(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold text-lg">&times;</button>
                                
                                <input type="text" :name="`tasks[${index}][title]`" x-model="task.title" required class="w-full border-0 border-b border-dashed border-gray-300 focus:ring-0 focus:border-indigo-500 font-bold text-gray-800 mb-2 p-0 pb-1" placeholder="Judul Tugas">
                                <textarea :name="`tasks[${index}][instructions]`" x-model="task.instructions" required rows="2" class="w-full border-0 focus:ring-0 p-0 text-sm text-gray-600 mb-2" placeholder="Instruksi spesifik..."></textarea>
                                <textarea :name="`tasks[${index}][goals]`" x-model="task.goals" rows="1" class="w-full border-0 focus:ring-0 p-0 text-sm text-gray-600 font-semibold mb-2" placeholder="Goals/Target (Opsional)"></textarea>
                                
                                <!-- Team Delegation -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Ketua Tim (Leader)</label>
                                        <select :name="`tasks[${index}][leader_id]`" x-model="task.leader_id" class="w-full text-sm rounded-lg border-gray-300">
                                            <option value="">-- Pilih Ketua Tim --</option>
                                            @foreach($workers as $worker)
                                                <option value="{{ $worker->id }}">{{ $worker->user->name }} ({{ $worker->skills->pluck('name')->join(', ') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Anggota Pendukung (Multiple)</label>
                                        <select :name="`tasks[${index}][member_ids][]`" x-model="task.member_ids" multiple class="w-full text-sm rounded-lg border-gray-300 h-20">
                                            @foreach($workers as $worker)
                                                <option value="{{ $worker->id }}">{{ $worker->user->name }} ({{ $worker->skills->pluck('name')->join(', ') }})</option>
                                            @endforeach
                                        </select>
                                        <p class="text-[10px] text-gray-500 mt-1">Gunakan Ctrl/Cmd+Click untuk memilih lebih dari 1</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 bg-indigo-50 px-3 py-2 rounded text-xs">
                                    <span class="font-bold text-indigo-700 whitespace-nowrap">Alasan AI:</span>
                                    <input type="text" :name="`tasks[${index}][reasoning]`" x-model="task.reasoning" class="w-full border-0 focus:ring-0 p-0 text-xs bg-transparent text-indigo-600" placeholder="Kenapa tugas/tim ini dipilih?">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.projects.import-wa.create') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">Batalkan</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Simpan & Delegasikan ke Tim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('waReview', () => ({
        tasks: @json($draft->ai_result['tasks'] ?? []),
        addTask() {
            this.tasks.push({ title: '', instructions: '', goals: '', reasoning: '', leader_id: '', member_ids: [] });
        },
        removeTask(index) {
            this.tasks.splice(index, 1);
        },
        init() {
            // Ensure task member_ids are arrays for multiple select
            this.tasks.forEach(t => {
                if (!Array.isArray(t.member_ids)) {
                    t.member_ids = [];
                }
            });
        }
    }))
})
</script>
