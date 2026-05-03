<!-- backend/resources/views/partials/doctor-ai-chat.blade.php -->
<div id="doctor-ai-container" class="fixed bottom-8 right-8 z-[9999]">
    
    <!-- Floating Toggle Button -->
    <button id="doctor-chat-toggle" class="flex items-center justify-center w-16 h-16 transition-all duration-500 bg-white shadow-2xl rounded-2xl hover:rounded-[2rem] hover:scale-110 active:scale-95 group cursor-pointer border border-blue-100">
        <div class="relative flex items-center justify-center pointer-events-none">
            <i class="fas fa-user-md text-2xl text-blue-600 group-hover:rotate-12 transition-transform"></i>
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-500 border-2 border-white"></span>
            </span>
        </div>
    </button>

    <!-- Chat Box (Floating, same as patient) -->
    <div id="doctor-chat-box" class="hidden absolute bottom-24 right-0 w-[420px] max-h-[700px] h-[80vh] flex flex-col bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-200 overflow-hidden transition-all duration-300 transform origin-bottom-right scale-95 opacity-0">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                        <i class="fas fa-user-md text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Secrétaire IA</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Outil de Productivité</p>
                    </div>
                </div>
                <button id="doctor-chat-close" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-2 gap-2">
                <button onclick="doctorAction('today_schedule')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer">
                    <i class="fas fa-calendar-day text-slate-400 group-hover:text-blue-600 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Agenda</span>
                </button>
                <button onclick="doctorAction('next_patient')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer">
                    <i class="fas fa-user-clock text-slate-400 group-hover:text-blue-600 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Prochain</span>
                </button>
            </div>
        </div>

        <!-- Chat Area -->
        <div id="doctor-chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-500/20">
                    <i class="fas fa-robot text-xs"></i>
                </div>
                <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-none shadow-sm max-w-[85%]">
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        Bonjour Docteur. Comment puis-je vous assister aujourd'hui ?
                    </p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 border-t border-slate-100 bg-white">
            <form id="doctor-chat-form" class="relative group">
                <input type="text" id="doctor-chat-input" autocomplete="off" 
                    placeholder="Tapez votre demande..." 
                    class="w-full h-12 pl-4 pr-12 text-sm font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-0 transition-all outline-none">
                <button type="submit" class="absolute right-2 top-2 w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-lg active:scale-90 cursor-pointer">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .doctor-chat-open { 
        display: flex !important; 
        transform: scale(1) !important; 
        opacity: 1 !important; 
    }
    #doctor-chat-messages::-webkit-scrollbar { width: 4px; }
    #doctor-chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script>
(function() {
    function init() {
        const toggle = document.getElementById('doctor-chat-toggle');
        const box = document.getElementById('doctor-chat-box');
        const close = document.getElementById('doctor-chat-close');
        const form = document.getElementById('doctor-chat-form');
        const input = document.getElementById('doctor-chat-input');
        const messages = document.getElementById('doctor-chat-messages');

        if (!toggle || !box) return;

        toggle.addEventListener('click', () => {
            box.classList.toggle('doctor-chat-open');
        });

        if (close) {
            close.addEventListener('click', () => {
                box.classList.remove('doctor-chat-open');
            });
        }

        if (form) {
            form.onsubmit = async (e) => {
                e.preventDefault();
                const msg = input.value.trim();
                if (!msg) return;

                appendMessage('user', msg);
                input.value = '';
                
                const typing = appendMessage('assistant', '...');

                try {
                    const response = await fetch('/ai/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: msg })
                    });
                    const data = await response.json();
                    typing.remove();
                    appendMessage('assistant', data.reply);
                } catch (error) {
                    if (typing) typing.remove();
                    appendMessage('assistant', 'Erreur de connexion.');
                }
            };
        }

        function appendMessage(role, text) {
            if (!messages) return;
            const div = document.createElement('div');
            div.className = `flex items-start space-x-3 ${role === 'user' ? 'flex-row-reverse space-x-reverse' : ''}`;
            
            const avatar = role === 'assistant' 
                ? `<div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0"><i class="fas fa-robot text-xs"></i></div>`
                : `<div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-500 shrink-0"><i class="fas fa-user text-xs"></i></div>`;

            div.innerHTML = `
                ${avatar}
                <div class="${role === 'assistant' ? 'bg-white border border-slate-200 text-slate-600' : 'bg-blue-600 text-white'} p-4 rounded-2xl ${role === 'assistant' ? 'rounded-tl-none' : 'rounded-tr-none'} max-w-[85%] shadow-sm">
                    <p class="text-sm leading-relaxed font-medium">${text}</p>
                </div>
            `;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
            return div;
        }

        window.doctorAction = function(action) {
            let prompt = "";
            if (action === 'today_schedule') prompt = "Montre-moi mon agenda d'aujourd'hui";
            if (action === 'next_patient') prompt = "Qui est mon prochain patient ?";
            input.value = prompt;
            form.dispatchEvent(new Event('submit'));
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
