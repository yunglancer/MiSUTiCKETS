@extends('layouts.admin')

@section('content')
<div class="font-display">
    {{-- 1. ENCABEZADO Y BIENVENIDA --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">speed</span>
                Panel de <span class="text-[#FF6600]">Control</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Gestión integral de la plataforma MiSUTiCKETS</p>
        </div>
        <a href="{{ route('admin.orders.pending') }}" class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-[#FF6600] transition-all shadow-lg shadow-slate-200">
            <span class="material-icons text-sm">payments</span>
            Validar Pagos
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-lg shadow-slate-200/50 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden mb-8">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#FF6600] rounded-full opacity-10 blur-2xl"></div>
        <div class="w-16 h-16 bg-slate-900 rounded-full flex items-center justify-center text-[#FF6600] shrink-0 z-10">
            <span class="material-icons text-3xl">admin_panel_settings</span>
        </div>
        <div class="z-10">
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">¡Hola, {{ auth()->user()->name }}!</h2>
            <p class="text-slate-500 text-sm leading-relaxed">
                Has iniciado sesión como <strong>{{ auth()->user()->roles->pluck('name')->first() }}</strong>. Tienes el control total de tus eventos y recaudaciones.
            </p>
        </div>
    </div>

    {{-- 2. TARJETAS DE MÉTRICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-100 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Recaudación Total</p>
            <h3 class="text-3xl font-black text-slate-900">REF. {{ number_format($totalRevenue, 2) }}</h3>
            <p class="text-emerald-600 text-[10px] font-bold mt-2 flex items-center gap-1 uppercase">
                <span class="material-icons text-xs">verified</span> Solo pagos aprobados
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-orange-100 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Entradas Emitidas</p>
            <h3 class="text-3xl font-black text-slate-900">{{ $ticketsSold }}</h3>
            <p class="text-[#FF6600] text-[10px] font-bold mt-2 uppercase">Tickets en circulación</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-slate-100 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Eventos Publicados</p>
            <h3 class="text-3xl font-black text-slate-900">{{ $activeEvents }}</h3>
            <p class="text-slate-500 text-[10px] font-bold mt-2 uppercase">Catálogo activo</p>
        </div>
    </div>

    {{-- 3. TABLA DE VENTAS RECIENTES CON COLUMNA DE EVENTO --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight italic">
                Últimos <span class="text-[#FF6600]">Movimientos</span>
            </h2>
            <span class="text-[10px] font-black bg-slate-100 text-slate-500 px-3 py-1 rounded-full uppercase">Sincronizado ahora</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Orden</th>
                        <th class="px-6 py-4">Evento / Cantidad</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Monto</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700 text-sm">#{{ $order->order_number }}</td>
                        
                        <td class="px-6 py-4">
                            @php
                                $firstTicket = $order->tickets->first();
                                $ticketCount = $order->tickets->count();
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 text-sm truncate max-w-[150px]" title="{{ $firstTicket?->event?->title }}">
                                    {{ $firstTicket?->event?->title ?? 'N/A' }}
                                </span>
                                <span class="bg-[#FF6600]/10 text-[#FF6600] text-[9px] font-black px-2 py-0.5 rounded-full border border-[#FF6600]/20 uppercase">
                                    {{ $ticketCount }} {{ Str::plural('Tkt', $ticketCount) }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900">{{ $order->user->name }}</p>
                            <p class="text-[10px] text-slate-400">{{ $order->user->email }}</p>
                        </td>

                        <td class="px-6 py-4 font-black text-slate-900 text-sm">
                            REF. {{ number_format($order->total_amount, 2) }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'paid' => 'bg-emerald-100 text-emerald-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'cancelled' => 'bg-rose-100 text-rose-700',
                                ];
                                $statusLabel = [
                                    'paid' => 'Pagada',
                                    'pending' => 'Pendiente',
                                    'cancelled' => 'Rechazada',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabel[$order->status] ?? $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <span class="material-icons text-slate-200 text-5xl mb-2">payments</span>
                            <p class="text-slate-400 font-medium">No hay ventas registradas todavía.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection