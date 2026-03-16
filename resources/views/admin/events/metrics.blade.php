@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="mb-8">
        <a href="{{ route('admin.events.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1 hover:text-[#FF6600] transition-colors mb-2">
            <span class="material-icons text-sm">arrow_back</span> Volver a eventos
        </a>
        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">{{ $event->title }}</h1>
        <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Métricas de Rendimiento e Inventario</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ingresos del Evento</p>
            <span class="text-2xl font-black text-slate-900">REF. {{ number_format($totalRevenue, 2) }}</span>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Entradas Vendidas</p>
            <span class="text-2xl font-black text-slate-900">{{ $totalSold }}</span>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Capacidad Total</p>
            <span class="text-2xl font-black text-slate-900">{{ number_format($event->venue->capacity) }}</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest">Ocupación por Zonas</h3>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($statsByZone as $zone)
            <div class="space-y-3">
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-[11px] font-black text-slate-800 uppercase tracking-tight">{{ $zone['name'] }}</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $zone['sold'] }} de {{ $zone['capacity'] }} vendidas</p>
                    </div>
                    <span class="text-[11px] font-black text-[#FF6600]">{{ number_format($zone['percentage'], 1) }}%</span>
                </div>
                
                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-[#FF6600] rounded-full transition-all duration-1000" style="width: {{ $zone['percentage'] }}%"></div>
                </div>

                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                    Recaudado en zona: <span class="text-slate-700">REF. {{ number_format($zone['revenue'], 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection