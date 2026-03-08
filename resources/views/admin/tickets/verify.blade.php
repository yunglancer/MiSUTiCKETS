@extends('layouts.admin')

@section('content')
<div class="font-display max-w-lg mx-auto mt-4 md:mt-10">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl overflow-hidden text-center">
        <div class="bg-slate-900 p-6 text-white">
            <h2 class="text-2xl font-black uppercase tracking-widest flex items-center justify-center gap-2">
                <span class="material-icons text-[#FF6600]">qr_code_scanner</span>
                Control de Acceso
            </h2>
            <p class="text-[#FF6600] text-xs font-bold uppercase tracking-widest mt-1">MiSUTiCKETS Puerta</p>
        </div>

        <div class="p-8">
            @if(session('error') || $ticket->status === 'used')
                <div class="bg-red-50 border-2 border-red-500 text-red-700 p-6 rounded-2xl mb-8 shadow-lg shadow-red-500/20 animate-pulse">
                    <span class="material-icons text-6xl mb-2">cancel</span>
                    <h3 class="text-2xl font-black uppercase tracking-widest">¡Acceso Denegado!</h3>
                    <p class="text-sm font-bold mt-2">{{ session('error') ?? 'Esta entrada ya fue escaneada y utilizada previamente.' }}</p>
                </div>
            @elseif(session('success'))
                <div class="bg-emerald-50 border-2 border-emerald-500 text-emerald-700 p-6 rounded-2xl mb-8 shadow-lg shadow-emerald-500/20">
                    <span class="material-icons text-6xl mb-2">check_circle</span>
                    <h3 class="text-2xl font-black uppercase tracking-widest">¡Adelante!</h3>
                    <p class="text-sm font-bold mt-2">{{ session('success') }}</p>
                </div>
            @else
                <div class="bg-blue-50 border-2 border-blue-500 text-blue-700 p-6 rounded-2xl mb-8 shadow-lg shadow-blue-500/20">
                    <span class="material-icons text-6xl mb-2">verified</span>
                    <h3 class="text-2xl font-black uppercase tracking-widest">Entrada Válida</h3>
                    <p class="text-sm font-bold mt-2">Lista para ser verificada.</p>
                </div>
            @endif

            <div class="text-left bg-slate-50 p-6 rounded-2xl border border-slate-100 mb-8 space-y-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Evento</p>
                    <p class="text-lg font-bold text-slate-800">{{ $ticket->event->title }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente / Titular de la cuenta</p>
                    <p class="text-lg font-black text-[#FF6600]">{{ $ticket->order->user->name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ticket ID</p>
                        <p class="text-sm font-mono font-bold text-slate-600">#{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Orden Asociada</p>
                        <p class="text-sm font-mono font-bold text-slate-600">#{{ str_pad($ticket->order_id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            @if($ticket->status !== 'used')
                <form action="{{ route('admin.tickets.markUsed', $ticket->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-slate-900 hover:bg-[#FF6600] text-white text-lg font-black py-5 px-6 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-900/30 flex items-center justify-center gap-2">
                        Quemar Entrada <span class="material-icons">how_to_reg</span>
                    </button>
                </form>
            @else
                <a href="{{ route('admin.dashboard') }}" class="block w-full bg-slate-200 text-slate-500 hover:bg-slate-300 text-sm font-black py-4 px-6 rounded-2xl uppercase tracking-[0.2em] transition-all">
                    Volver al Inicio
                </a>
            @endif
        </div>
    </div>
</div>
@endsection