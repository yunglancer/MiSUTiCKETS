<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight uppercase tracking-widest flex items-center gap-2">
            <span class="material-icons text-[#FF6600]">space_dashboard</span>
            {{ __('Mi Panel') }} <span class="text-[#FF6600]">MisuTickets</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen font-display">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-slate-100">
                <div class="p-8 flex flex-col md:flex-row items-center gap-6">
                    <div class="w-20 h-20 bg-[#FF6600]/10 rounded-full flex items-center justify-center text-[#FF6600]">
                        <span class="material-icons text-4xl">person</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight">
                            ¡Bienvenido, <span class="text-[#FF6600]">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                        </h3>
                        <div class="mt-3 flex flex-wrap gap-6 text-sm font-bold text-slate-500 uppercase tracking-widest">
                            <p class="flex items-center gap-1"><span class="material-icons text-[16px] text-slate-400">badge</span> Cédula: {{ Auth::user()->document_id }}</p>
                            <p class="flex items-center gap-1"><span class="material-icons text-[16px] text-slate-400">phone</span> Teléfono: {{ Auth::user()->phone }}</p>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-[#FF6600] uppercase tracking-widest transition-colors">
                                <span class="material-icons text-sm">settings</span> Configurar Perfil
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="material-icons text-[#FF6600]">confirmation_number</span>
                    Mis Entradas Activas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($tickets as $ticket)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col transition-transform hover:-translate-y-1">
                            <div class="bg-slate-900 p-6 text-white relative overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#FF6600] rounded-full opacity-20 blur-xl"></div>
                                <span class="bg-[#FF6600] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full inline-block mb-3">
                                    Pase Confirmado
                                </span>
                                <h3 class="text-xl font-bold leading-tight mb-1">{{ $ticket->event->title ?? 'Evento Próximamente' }}</h3>
                                <p class="text-slate-400 text-xs tracking-widest">ID: {{ strtoupper(substr($ticket->ticket_code, 0, 8)) }}</p>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Titular</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $ticket->user->name }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Estado</p>
                                            <p class="text-sm font-bold text-emerald-500 uppercase flex items-center gap-1">
                                                <span class="material-icons text-[14px]">check_circle</span> Activo
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 pt-6 border-t border-slate-200 border-dashed flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <button class="text-slate-500 text-[10px] font-black uppercase tracking-widest hover:text-[#FF6600] transition-colors flex items-center gap-1">
                                        <span class="material-icons text-[16px]">qr_code_scanner</span> Ver QR
                                    </button>
                                    
                                    <a href="{{ route('client.ticket.download', $ticket->id) }}" target="_blank" class="w-full sm:w-auto bg-slate-900 hover:bg-[#FF6600] text-white text-[10px] font-black py-3 px-4 rounded-xl uppercase tracking-widest transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-[#FF6600]/30">
                                        Descargar PDF <span class="material-icons text-[16px]">download</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                            <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <span class="material-icons text-4xl text-slate-300">sentiment_dissatisfied</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Aún no tienes entradas</h3>
                            <p class="text-slate-500 text-sm mb-6 max-w-md mx-auto">Parece que todavía no has comprado boletos para ninguno de nuestros eventos. ¡No te quedes por fuera!</p>
                            <a href="{{ route('home') }}" class="inline-block bg-[#FF6600] hover:bg-slate-900 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-[#FF6600]/30">
                                Explorar Eventos
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>