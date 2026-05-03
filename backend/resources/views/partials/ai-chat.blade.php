<!-- backend/resources/views/partials/ai-chat.blade.php -->
<div id="patient-ai-container" class="fixed bottom-8 right-8 z-[9999]">
    
    <!-- Floating Toggle Button -->
    <button id="chat-toggle" class="flex items-center justify-center w-16 h-16 transition-all duration-300 bg-white shadow-2xl rounded-2xl hover:rounded-[2rem] hover:scale-110 active:scale-95 group cursor-pointer border border-blue-100">
        <div class="relative flex items-center justify-center pointer-events-none">
            <i class="fas fa-hand-holding-medical text-2xl text-blue-600 group-hover:rotate-12 transition-transform"></i>
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-500 border-2 border-white"></span>
            </span>
        </div>
    </button>

    <!-- Patient Chat Box -->
    <div id="chat-box" class="hidden absolute bottom-24 right-0 w-[420px] max-h-[700px] h-[80vh] flex flex-col bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-200 overflow-hidden transition-all duration-300 transform origin-bottom-right scale-95 opacity-0">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                        <i class="fas fa-robot text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Assistant TBIB.AI</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Votre Compagnon Santé</p>
                    </div>
                </div>
                <button id="chat-close" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Dynamic Quick Actions Grid -->
            <div id="quick-actions" class="grid grid-cols-2 gap-2">
                <!-- Initial Buttons -->
                <button onclick="quickAction('RDV')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer shadow-sm">
                    <i class="fas fa-calendar-alt text-slate-400 group-hover:text-blue-600 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Mes RDV</span>
                </button>
                <button onclick="quickAction('Symptômes')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer shadow-sm">
                    <i class="fas fa-stethoscope text-slate-400 group-hover:text-blue-600 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Symptômes</span>
                </button>
                <button onclick="quickAction('Médecins')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer shadow-sm">
                    <i class="fas fa-user-md text-slate-400 group-hover:text-blue-600 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Nos Tbibs</span>
                </button>
                <button onclick="quickAction('Aide')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer shadow-sm">
                    <i class="fas fa-question-circle text-slate-400 group-hover:text-blue-600 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Aide</span>
                </button>
            </div>
        </div>

        <!-- Chat Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30">
            <!-- Initial Welcome & Starters -->
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-500/20">
                    <i class="fas fa-robot text-xs"></i>
                </div>
                <div class="space-y-4 max-w-[85%]">
                    <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-none shadow-sm">
                        <p class="text-sm text-slate-600 leading-relaxed font-medium">
                            Bonjour ! Je suis votre assistant **TBIB.AI**. <br/><br/>
                            Comment puis-je vous aider aujourd'hui ? 👋
                        </p>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Essayez de me demander :</p>
                        <div class="grid grid-cols-1 gap-2">
                            <button onclick="quickAction('Starter_RDV')" class="text-left p-3 bg-white border border-slate-100 rounded-xl hover:border-blue-500 hover:shadow-md transition-all text-xs text-slate-600 font-medium">
                                "Voir mes prochains rendez-vous"
                            </button>
                            <button onclick="quickAction('Starter_Book')" class="text-left p-3 bg-white border border-slate-100 rounded-xl hover:border-blue-500 hover:shadow-md transition-all text-xs text-slate-600 font-medium">
                                "Prendre RDV avec un cardiologue"
                            </button>
                            <button onclick="quickAction('Starter_Symptoms')" class="text-left p-3 bg-white border border-slate-100 rounded-xl hover:border-blue-500 hover:shadow-md transition-all text-xs text-slate-600 font-medium">
                                "J'ai mal à la tête depuis 3 jours"
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="typing-indicator" class="hidden px-6 py-2 bg-slate-50/30">
            <div class="flex items-center space-x-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <div class="flex space-x-1">
                    <div class="w-1 h-1 bg-blue-400 rounded-full animate-bounce"></div>
                    <div class="w-1 h-1 bg-blue-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                    <div class="w-1 h-1 bg-blue-400 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                </div>
                <span>TBIB Assistant écrit...</span>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 border-t border-slate-100 bg-white">
            <form id="chat-form" class="relative group">
                <input type="text" id="chat-input" autocomplete="off" 
                    placeholder="Comment puis-je vous aider ?" 
                    class="w-full h-12 pl-4 pr-12 text-sm font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-0 transition-all outline-none">
                <button type="submit" class="absolute right-2 top-2 w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-lg active:scale-90 cursor-pointer">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
            <div class="flex items-center justify-center mt-4 space-x-2 text-[9px] font-bold uppercase tracking-widest text-slate-400 italic">
                <i class="fas fa-shield-alt text-[10px] text-blue-500/50"></i>
                <span>IA Consultative • Consultez toujours un Tbib</span>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-open { 
        display: flex !important; 
        transform: scale(1) !important; 
        opacity: 1 !important; 
    }
    #chat-messages::-webkit-scrollbar { width: 4px; }
    #chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .message-card {
        @apply bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm;
    }
</style>

<script>
(function() {
    function init() {
        const toggle = document.getElementById('chat-toggle');
        const box = document.getElementById('chat-box');
        const close = document.getElementById('chat-close');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('chat-input');
        const messages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');
        const quickActionsContainer = document.getElementById('quick-actions');

        if (!toggle || !box) return;

        toggle.addEventListener('click', () => {
            box.classList.toggle('chat-open');
        });

        if (close) {
            close.addEventListener('click', () => {
                box.classList.remove('chat-open');
            });
        }

        if (form) {
            form.onsubmit = async (e) => {
                e.preventDefault();
                const msg = input.value.trim();
                if (!msg) return;

                await sendMessage(msg);
            };
        }

        async function sendMessage(msg) {
            appendMessage('user', msg);
            input.value = '';
            
            showTyping(true);
            const startTime = Date.now();

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
                const duration = ((Date.now() - startTime) / 1000).toFixed(1);
                
                showTyping(false);
                
                if (data.component) {
                    renderComponent(data.component, data.component_data);
                }
                
                appendMessage('assistant', data.reply, duration);
                
                if (data.intent && data.intent.action) {
                    updateQuickActions(data.intent.action, data.buttons);
                }
            } catch (error) {
                showTyping(false);
                appendMessage('assistant', '😔 L\'assistant est momentanément indisponible. Vous pouvez prendre rendez-vous directement depuis votre tableau de bord.');
                renderComponent('error_fallback');
            }
        }

        function showTyping(show) {
            if (show) {
                typingIndicator.classList.remove('hidden');
                messages.scrollTop = messages.scrollHeight;
            } else {
                typingIndicator.classList.add('hidden');
            }
        }

        function appendMessage(role, text, duration = null) {
            if (!messages) return;
            const div = document.createElement('div');
            div.className = `flex items-start space-x-3 ${role === 'user' ? 'flex-row-reverse space-x-reverse' : ''}`;
            
            const avatar = role === 'assistant' 
                ? `<div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-500/20"><i class="fas fa-robot text-xs"></i></div>`
                : `<div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-500 shrink-0"><i class="fas fa-user text-xs"></i></div>`;

            const timeLabel = duration ? `<p class="text-[8px] text-slate-400 mt-1 text-right">Réponse en ${duration}s</p>` : '';

            div.innerHTML = `
                ${avatar}
                <div class="${role === 'assistant' ? 'bg-white border border-slate-200 text-slate-600' : 'bg-blue-600 text-white'} p-4 rounded-2xl ${role === 'assistant' ? 'rounded-tl-none' : 'rounded-tr-none'} max-w-[85%] shadow-sm">
                    <p class="text-sm leading-relaxed font-medium">${text.replace(/\n/g, '<br>')}</p>
                    ${timeLabel}
                </div>
            `;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
            return div;
        }

        function renderComponent(type, data = {}) {
            const div = document.createElement('div');
            div.className = "w-full pl-11 pr-4 py-2";
            
            let html = '';
            
            if (type === 'appointment_confirmation') {
                html = `
                    <div class="bg-white border-2 border-emerald-100 rounded-2xl overflow-hidden shadow-md">
                        <div class="bg-emerald-50 px-4 py-2 border-b border-emerald-100 flex items-center justify-between">
                            <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">✅ RDV Confirmé</span>
                            <i class="fas fa-check-circle text-emerald-500"></i>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">${data.doctor_name || 'Dr. Hsin Aïssi'}</p>
                                    <p class="text-[10px] text-slate-500">${data.specialty || 'Généraliste'}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-slate-50 p-2 rounded-lg">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Date</p>
                                    <p class="text-[10px] font-bold text-slate-700">${data.date || '---'}</p>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Heure</p>
                                    <p class="text-[10px] font-bold text-slate-700">${data.time || '---'}</p>
                                </div>
                            </div>
                            <button onclick="quickAction('RDV')" class="w-full py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20">
                                Voir mes rendez-vous
                            </button>
                        </div>
                    </div>
                `;
            } else if (type === 'appointment_list') {
                const appts = data.appointments || [];
                let apptHtml = appts.map(a => `
                    <div class="border-b border-slate-100 last:border-0 py-3 space-y-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-slate-900">Dr. ${a.doctor_name}</p>
                                <p class="text-[10px] text-slate-500">${a.specialty}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[8px] font-black rounded-full uppercase tracking-tighter">✅ Confirmé</span>
                        </div>
                        <p class="text-[10px] font-medium text-slate-600"><i class="far fa-calendar-alt mr-1 text-blue-500"></i> ${a.date} à ${a.time}</p>
                        <div class="flex space-x-2 pt-1">
                            <button class="flex-1 py-1.5 border border-slate-200 rounded-lg text-[9px] font-bold text-slate-500 hover:bg-slate-50 transition-all">Annuler</button>
                            <button class="flex-1 py-1.5 border border-slate-200 rounded-lg text-[9px] font-bold text-slate-500 hover:bg-slate-50 transition-all">Reprogrammer</button>
                        </div>
                    </div>
                `).join('');
                
                html = `
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="bg-slate-50 px-4 py-2 border-b border-slate-100">
                            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">📅 Vos Prochains RDV</span>
                        </div>
                        <div class="p-4 max-h-[250px] overflow-y-auto">
                            ${apptHtml || '<p class="text-xs text-slate-400 text-center py-4 italic">Aucun rendez-vous à venir.</p>'}
                        </div>
                    </div>
                `;
            } else if (type === 'error_fallback') {
                html = `
                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                        <a href="/dashboard" class="flex items-center justify-center space-x-2 w-full py-2 bg-white border border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all">
                            <i class="fas fa-home"></i>
                            <span>Aller au tableau de bord</span>
                        </a>
                    </div>
                `;
            }

            div.innerHTML = html;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function updateQuickActions(action, customButtons = null) {
            let buttons = [];
            
            if (customButtons && customButtons.length > 0) {
                buttons = customButtons;
            } else if (action === 'book_appointment_intent') {
                buttons = [
                    { label: '✅ Confirmer', action: 'Confirmer' },
                    { label: '❌ Annuler', action: 'Annuler' },
                    { label: '🔄 Changer date', action: 'Changer_Date' },
                    { label: '👨‍⚕️ Autre Tbib', action: 'Autre_Tbib' }
                ];
            } else if (action === 'appointment_booked') {
                buttons = [
                    { label: '📅 Mes RDV', action: 'RDV' },
                    { label: '🏠 Accueil', action: 'Accueil' }
                ];
            } else {
                buttons = [
                    { label: '📅 Mes RDV', action: 'RDV' },
                    { label: '🩺 Symptômes', action: 'Symptômes' },
                    { label: '👨‍⚕️ Nos Tbibs', action: 'Médecins' },
                    { label: '❓ Aide', action: 'Aide' }
                ];
            }

            quickActionsContainer.innerHTML = buttons.map(b => `
                <button onclick="quickAction('${b.action}')" class="flex items-center space-x-2 p-3 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group cursor-pointer shadow-sm">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 group-hover:text-blue-700">${b.label}</span>
                </button>
            `).join('');
        }

        window.quickAction = function(action) {
            let prompt = "";
            switch(action) {
                case 'RDV': prompt = "Voir mes prochains rendez-vous"; break;
                case 'Symptômes': prompt = "J'aimerais analyser mes symptômes"; break;
                case 'Médecins': prompt = "Quels sont les médecins disponibles ?"; break;
                case 'Aide': prompt = "Que peux-tu faire pour moi ?"; break;
                case 'Confirmer': prompt = "Oui, je confirme ce rendez-vous"; break;
                case 'Annuler': prompt = "Non, annule tout"; break;
                case 'Starter_RDV': prompt = "Voir mes prochains rendez-vous"; break;
                case 'Starter_Book': prompt = "Prendre RDV avec un cardiologue"; break;
                case 'Starter_Symptoms': prompt = "J'ai mal à la tête depuis 3 jours"; break;
                case 'Accueil': location.reload(); return;
            }
            if (input && form) {
                input.value = prompt;
                form.dispatchEvent(new Event('submit'));
            }
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
