<div x-data="aiChatWidget()" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50">
    <!-- Floating Trigger Button -->
    <button 
        @click="toggleChat()" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-3.5 sm:p-4 shadow-xl shadow-indigo-600/30 transition-all duration-300 hover:scale-105 flex items-center justify-center focus:outline-none"
        :class="isOpen ? 'scale-0 opacity-0 pointer-events-none' : 'scale-100 opacity-100'"
        title="Tanya Konsultan AI"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
        </span>
    </button>

    <!-- Chat Window Container -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="bg-white rounded-3xl shadow-2xl w-[90vw] sm:w-[380px] max-w-[380px] border border-slate-200 flex flex-col absolute bottom-0 right-0 overflow-hidden"
        style="height: 480px; max-height: 80vh; display: none;"
    >
        <!-- Header -->
        <div class="bg-slate-900 text-white p-4 flex justify-between items-center border-b border-slate-800">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                    🤖
                </div>
                <div>
                    <h3 class="font-extrabold text-xs text-white">Konsultan AI TEFA</h3>
                    <p class="text-[10px] text-indigo-300 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Online • Siap Membantu
                    </p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Chat Conversation Messages -->
        <div class="flex-1 p-4 overflow-y-auto bg-slate-50 flex flex-col gap-3" id="chat-messages-container">
            <div class="flex gap-2 items-start">
                <div class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">
                    🤖
                </div>
                <div class="bg-white border border-slate-200/80 text-slate-800 text-xs rounded-2xl rounded-tl-none px-3.5 py-2.5 shadow-sm leading-relaxed">
                    Halo! Ada yang bisa saya bantu terkait produk, jasa, atau konsultasi kebutuhan proyek Anda di TEFA?
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.sender === 'user' ? 'flex flex-row-reverse gap-2 items-start' : 'flex gap-2 items-start'">
                    <div x-show="msg.sender === 'ai'" class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">🤖</div>
                    <div 
                        :class="msg.sender === 'user' ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white border border-slate-200/80 text-slate-800 rounded-tl-none'"
                        class="text-xs rounded-2xl px-3.5 py-2.5 shadow-sm max-w-[82%] leading-relaxed"
                        x-text="msg.text"
                    ></div>
                </div>
            </template>
            
            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex gap-2 items-start" style="display:none;">
                <div class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">🤖</div>
                <div class="bg-white border border-slate-200 text-slate-600 text-xs rounded-2xl rounded-tl-none px-3.5 py-3 shadow-sm flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-slate-200">
            @auth
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input 
                        x-model="newMessage" 
                        type="text" 
                        placeholder="Tanya harga, estimasi, spek..." 
                        class="w-full rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-xs font-medium"
                        :disabled="isLoading"
                    >
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl w-9 h-9 flex items-center justify-center flex-shrink-0 disabled:opacity-50 transition shadow-sm" :disabled="isLoading || newMessage.trim() === ''">
                        <svg class="w-3.5 h-3.5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            @else
                <div class="text-center p-2.5 bg-indigo-50/70 rounded-xl border border-indigo-100">
                    <p class="text-[11px] font-medium text-indigo-900 mb-2">Masuk untuk berkonsultasi dengan AI</p>
                    <a href="{{ route('login') }}" class="block w-full text-center bg-indigo-600 text-white rounded-lg py-1.5 text-xs font-bold hover:bg-indigo-700 transition">Login / Daftar Sekarang</a>
                </div>
            @endauth
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('aiChatWidget', () => ({
        isOpen: false,
        isLoading: false,
        newMessage: '',
        sessionToken: localStorage.getItem('tefa_ai_session') || '',
        messages: [],
        
        toggleChat() {
            this.isOpen = !this.isOpen;
            if(this.isOpen) {
                this.scrollToBottom();
            }
        },

        async sendMessage() {
            if (this.newMessage.trim() === '') return;
            
            const msgText = this.newMessage;
            this.messages.push({ sender: 'user', text: msgText });
            this.newMessage = '';
            this.isLoading = true;
            this.scrollToBottom();

            try {
                const response = await fetch('{{ route('api.ai.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: msgText,
                        session_token: this.sessionToken,
                        tefa_unit_id: window.TEFA_UNIT_ID || null 
                    })
                });

                if (response.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                const data = await response.json();
                
                if (data.session_token) {
                    this.sessionToken = data.session_token;
                    localStorage.setItem('tefa_ai_session', data.session_token);
                }

                this.messages.push({ sender: 'ai', text: data.reply });
            } catch (error) {
                console.error('Chat error:', error);
                this.messages.push({ sender: 'ai', text: 'Koneksi AI sedang tidak stabil. Silakan coba beberapa saat lagi.' });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },

        scrollToBottom() {
            setTimeout(() => {
                const container = document.getElementById('chat-messages-container');
                if(container) container.scrollTop = container.scrollHeight;
            }, 50);
        }
    }));
});
</script>
