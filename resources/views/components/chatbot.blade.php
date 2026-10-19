<!-- AI Chatbot Floating Widget (Bottom-Right) -->
<div x-data="aiChatbot()" x-cloak class="fixed bottom-5 right-5 z-50 font-sans">
    
    <!-- Chat Window Container -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 scale-95"
         class="w-[92vw] sm:w-[400px] h-[540px] max-h-[82vh] bg-white rounded-2xl shadow-2xl border border-[#D8E1EA] flex flex-col overflow-hidden mb-3">
        
        <!-- Header -->
        <div class="px-4 py-3.5 bg-gradient-to-r from-[#123B6D] to-[#0E7C9E] text-white flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 rounded-full bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <!-- Active Indicator -->
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#123B6D]"></span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold tracking-tight">Si Pringgo</h3>
                        <span class="px-1.5 py-0.2 text-[9px] font-bold uppercase tracking-wider bg-white/20 text-white rounded">AI Bot</span>
                    </div>
                    <p class="text-[11px] text-white/80">Asisten Wisata & UMKM Pringsewu</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-1">
                <button @click="resetChat()" type="button" class="p-1.5 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" title="Bersihkan Percakapan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
                <button @click="toggleChat()" type="button" class="p-1.5 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" title="Tutup Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages Area -->
        <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3.5 bg-gray-50/60 text-xs">
            
            <!-- Greeting Notification Banner -->
            <div class="p-2.5 rounded-xl bg-[#EEF3F8] border border-[#D8E1EA] text-[#4A5A6B] text-[11px] flex items-start gap-2">
                <span class="text-base flex-shrink-0">💡</span>
                <p>Tanyakan rekomendasi tempat wisata, rute, harga tiket, kuliner UMKM, atau informasi terbaru di Pringsewu.</p>
            </div>

            <!-- Messages Loop -->
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                    <div class="flex items-end gap-2 max-w-[86%]" :class="msg.sender === 'user' ? 'flex-row-reverse' : 'flex-row'">
                        
                        <!-- Avatar for bot -->
                        <template x-if="msg.sender === 'bot'">
                            <div class="w-6 h-6 rounded-full bg-[#123B6D] text-white flex items-center justify-center text-[10px] flex-shrink-0 mb-1">
                                🤖
                            </div>
                        </template>

                        <!-- Bubble -->
                        <div class="px-3.5 py-2.5 rounded-2xl shadow-sm text-[12px] leading-relaxed break-words"
                             :class="msg.sender === 'user' 
                                 ? 'bg-[#123B6D] text-white rounded-br-none' 
                                 : 'bg-white text-[#12181F] border border-[#D8E1EA] rounded-bl-none'">
                            <div x-html="formatMessage(msg.text)"></div>
                            <span class="block text-[9px] mt-1 text-right"
                                  :class="msg.sender === 'user' ? 'text-white/60' : 'text-gray-400'"
                                  x-text="msg.time"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator Bubble -->
            <div x-show="isTyping" class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-[#123B6D] text-white flex items-center justify-center text-[10px] flex-shrink-0">
                    🤖
                </div>
                <div class="px-3.5 py-2.5 bg-white border border-[#D8E1EA] rounded-2xl rounded-bl-none shadow-sm flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0E7C9E] animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0E7C9E] animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0E7C9E] animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>

            <!-- Suggestions Pills (Dynamic) -->
            <div x-show="suggestions.length > 0 && !isTyping" class="pt-2">
                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-1.5 pl-1">Pertanyaan Cepat:</p>
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="(sug, index) in suggestions" :key="index">
                        <button @click="sendSuggestion(sug)"
                                type="button" 
                                class="px-2.5 py-1 rounded-full bg-white hover:bg-[#EEF3F8] text-[#0E7C9E] hover:text-[#123B6D] border border-[#D8E1EA] text-[11px] font-medium transition-colors shadow-2xs">
                            <span x-text="sug"></span>
                        </button>
                    </template>
                </div>
            </div>

        </div>

        <!-- Input Area Footer -->
        <div class="p-3 bg-white border-t border-[#D8E1EA]">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <input type="text" 
                       x-model="inputMessage" 
                       x-ref="chatInput"
                       :disabled="isTyping"
                       placeholder="Ketik pertanyaan seputar Pringsewu..." 
                       class="flex-1 px-3.5 py-2 text-xs rounded-xl border border-[#D8E1EA] focus:outline-none focus:border-[#0E7C9E] bg-gray-50 focus:bg-white transition-colors disabled:opacity-50">
                <button type="submit" 
                        :disabled="!inputMessage.trim() || isTyping"
                        class="p-2 rounded-xl bg-[#0E7C9E] hover:bg-[#0b627d] disabled:opacity-40 disabled:hover:bg-[#0E7C9E] text-white transition-colors flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
            <div class="flex items-center justify-between mt-2 px-1 text-[10px] text-gray-400">
                <span>Si Pringgo AI · SIG Pringsewu</span>
                <span class="text-[#0E7C9E] font-medium">Online 24/7</span>
            </div>
        </div>
    </div>

    <!-- Floating Action Button (FAB) -->
    <div class="flex items-center justify-end gap-2.5">
        <!-- Floating Prompt Bubble (shows when closed) -->
        <div x-show="!isOpen && showHint" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-3"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-3"
             class="hidden sm:flex items-center gap-2 px-3.5 py-2 rounded-full bg-white border border-[#D8E1EA] shadow-lg text-[12px] font-semibold text-[#123B6D] cursor-pointer hover:bg-gray-50 transition-colors"
             @click="toggleChat()">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Tanya AI Wisata Pringsewu ✨</span>
            <button @click.stop="showHint = false" class="text-gray-400 hover:text-gray-600 pl-1 text-[10px]">✕</button>
        </div>

        <!-- Main FAB Button -->
        <button @click="toggleChat()" 
                type="button" 
                class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#123B6D] via-[#0E7C9E] to-[#38bdf8] text-white shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center relative focus:outline-none focus:ring-4 focus:ring-[#0E7C9E]/30"
                aria-label="Buka Chatbot AI">
            
            <!-- Bot Icon (when closed) -->
            <div x-show="!isOpen" class="flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>

            <!-- Close Icon (when open) -->
            <div x-show="isOpen" x-cloak class="flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>

            <!-- Ping effect when closed -->
            <span x-show="!isOpen" class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-white"></span>
            </span>
        </button>
    </div>
</div>

<script>
    function aiChatbot() {
        return {
            isOpen: false,
            showHint: true,
            inputMessage: '',
            isTyping: false,
            messages: [
                {
                    id: 1,
                    sender: 'bot',
                    text: 'Halo! Saya **Si Pringgo**, asisten AI cerdas **Pringsewu.com** 🎋\n\nAda yang bisa saya bantu terkait destinasi wisata, sentra UMKM, atau harga tiket di Pringsewu hari ini?',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                }
            ],
            suggestions: [
                'Wisata Populer',
                'Sentra UMKM & Kuliner',
                'Harga Tiket Masuk',
                'Buka Peta Wisata'
            ],

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.showHint = false;
                    this.scrollToBottom();
                    setTimeout(() => {
                        if (this.$refs.chatInput) {
                            this.$refs.chatInput.focus();
                        }
                    }, 150);
                }
            },

            resetChat() {
                this.messages = [
                    {
                        id: Date.now(),
                        sender: 'bot',
                        text: 'Percakapan telah direset. Silakan tanyakan apa pun mengenai destinasi wisata, UMKM, rute peta, atau kabar berita di Kabupaten Pringsewu! 😊🌿',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    }
                ];
                this.suggestions = ['Wisata Populer', 'Sentra UMKM & Kuliner', 'Harga Tiket Masuk', 'Buka Peta Wisata'];
                this.scrollToBottom();
            },

            sendSuggestion(text) {
                this.inputMessage = text;
                this.sendMessage();
            },

            async sendMessage() {
                const text = this.inputMessage.trim();
                if (!text || this.isTyping) return;

                // Push user message
                this.messages.push({
                    id: Date.now(),
                    sender: 'user',
                    text: text,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });

                this.inputMessage = '';
                this.isTyping = true;
                this.scrollToBottom();

                try {
                    const response = await fetch("{{ route('chatbot.message') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: text })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.messages.push({
                            id: Date.now() + 1,
                            sender: 'bot',
                            text: data.reply || 'Maaf, saya tidak dapat memproses jawaban saat ini.',
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });

                        if (data.suggestions && Array.isArray(data.suggestions)) {
                            this.suggestions = data.suggestions;
                        }
                    } else {
                        throw new Error('Gagal menghubungi server.');
                    }
                } catch (error) {
                    this.messages.push({
                        id: Date.now() + 1,
                        sender: 'bot',
                        text: '⚠️ Maaf, terjadi kendala koneksi ke server. Silakan coba kembali beberapa saat lagi.',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } finally {
                    this.isTyping = false;
                    this.scrollToBottom();
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = document.getElementById('chatbot-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            },

            formatMessage(text) {
                if (!text) return '';
                
                // Escape HTML tags to prevent XSS
                let safe = text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;");

                // Convert markdown bold **text** to <strong>
                safe = safe.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-[#123B6D]">$1</strong>');
                
                // Convert markdown italic *text* to <em>
                safe = safe.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');

                // Convert markdown links [Label](URL) to <a>
                safe = safe.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" class="font-semibold text-[#0E7C9E] hover:underline underline-offset-2">$1 &rarr;</a>');

                // Convert newlines to <br>
                safe = safe.replace(/\n/g, '<br>');

                return safe;
            }
        };
    }
</script>
