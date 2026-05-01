<div id="ai-chat-container" class="fixed bottom-8 right-8 z-50">
    <!-- Chat Toggle Button -->
    <button id="chat-toggle" class="gradient-primary text-white w-16 h-16 rounded-2xl shadow-2xl shadow-blue-500/40 hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center group relative">
        <span class="absolute -top-1 -right-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-500 border-2 border-white"></span>
        </span>
        <i class="fas fa-robot text-2xl group-hover:rotate-12 transition-transform"></i>
    </button>

    <!-- Chat Box -->
    <div id="chat-box" class="hidden absolute bottom-24 right-0 w-[400px] bg-white rounded-3xl shadow-2xl border border-slate-100 flex flex-col overflow-hidden transition-all duration-500 transform origin-bottom-right">
        <!-- Header -->
        <div class="gradient-primary p-6 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                        <i class="fas fa-robot text-xl"></i>
                    </div>
                    <div>
                        <span class="block font-black tracking-tight">Assistant IA TBIB</span>
                        <div class="flex items-center text-[10px] font-bold uppercase tracking-widest text-blue-100">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Toujours en ligne
                        </div>
                    </div>
                </div>
                <button id="chat-close" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 transition-colors text-white/80">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="flex-1 h-[450px] overflow-y-auto p-6 space-y-6 bg-slate-50/50">
            <div class="flex justify-start">
                <div class="bg-white border border-slate-100 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[85%] text-sm text-slate-600 leading-relaxed">
                    Bonjour ! Je suis votre assistant IA spécialisé. <br/><br/>Comment puis-je vous aider aujourd'hui ? 
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-blue-100 transition-colors">Symptômes</button>
                        <button class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-blue-100 transition-colors">Rendez-vous</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-slate-100">
            <form id="chat-form" class="relative">
                <input type="text" id="chat-input" placeholder="Décrivez votre problème..." class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none pr-14">
                <button type="submit" class="absolute right-2 top-2 bottom-2 w-10 gradient-primary text-white rounded-xl shadow-lg shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
            <p class="text-[10px] text-center text-slate-400 mt-4 font-medium italic">
                L'IA peut faire des erreurs. Consultez toujours un médecin.
            </p>
        </div>
    </div>
</div>

<script>
    const chatToggle = document.getElementById('chat-toggle');
    const chatClose = document.getElementById('chat-close');
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');

    chatToggle.addEventListener('click', () => {
        chatBox.classList.toggle('hidden');
        if (!chatBox.classList.contains('hidden')) {
            chatInput.focus();
        }
    });
    
    chatClose.addEventListener('click', () => chatBox.classList.add('hidden'));

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        appendMessage('user', message);
        chatInput.value = '';

        // Add loading state
        const loadingId = 'loading-' + Date.now();
        appendLoadingMessage(loadingId);

        try {
            const response = await fetch('/ai/process-appointment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            document.getElementById(loadingId).remove();
            
            if (data.error) {
                appendMessage('ai', 'Désolé, je rencontre des difficultés techniques : ' + data.error);
            } else {
                appendMessage('ai', data.message || data.orientation || 'J\'ai bien reçu votre message.');
            }
        } catch (error) {
            document.getElementById(loadingId).remove();
            appendMessage('ai', 'Désolé, une erreur est survenue.');
        }
    });

    function appendMessage(sender, text) {
        const div = document.createElement('div');
        div.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
        
        const content = document.createElement('div');
        if (sender === 'user') {
            content.className = 'gradient-primary text-white p-4 rounded-2xl rounded-tr-none shadow-lg shadow-blue-500/10 max-w-[85%] text-sm leading-relaxed';
        } else {
            content.className = 'bg-white border border-slate-100 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[85%] text-sm text-slate-600 leading-relaxed';
        }
        content.textContent = text;
        
        div.appendChild(content);
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendLoadingMessage(id) {
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex justify-start';
        div.innerHTML = `
            <div class="bg-white border border-slate-100 p-4 rounded-2xl rounded-tl-none shadow-sm flex items-center space-x-2">
                <div class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce"></div>
                <div class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                <div class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
            </div>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
</script>
