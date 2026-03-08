@extends('layouts.admin')

@section('content')
<div class="font-display">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">analytics</span>
                Panel de Control <span class="text-[#FF6600]">Admin</span>
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Resumen Financiero y Operativo</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500 rounded-full opacity-10 blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center shrink-0">
                <span class="material-icons text-3xl">payments</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Ingresos Totales</p>
                <h3 class="text-3xl font-black text-slate-900">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#FF6600] rounded-full opacity-10 blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-16 h-16 bg-[#FF6600]/10 text-[#FF6600] rounded-2xl flex items-center justify-center shrink-0">
                <span class="material-icons text-3xl">local_activity</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tickets Emitidos</p>
                <h3 class="text-3xl font-black text-slate-900">{{ $ticketsSold }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500 rounded-full opacity-10 blur-xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center shrink-0">
                <span class="material-icons text-3xl">event</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Eventos Activos</p>
                <h3 class="text-3xl font-black text-slate-900">{{ $activeEvents }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-900 text-white">
            <h3 class="text-lg font-bold uppercase tracking-widest flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">receipt_long</span> Últimas Transacciones
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Orden ID</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Monto</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5 whitespace-nowrap font-mono font-bold text-slate-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-5 whitespace-nowrap font-bold text-slate-700">{{ $order->user->name }}</td>
                            <td class="px-6 py-5 whitespace-nowrap text-slate-500 text-xs font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                           <td class="px-6 py-5 whitespace-nowrap font-black text-emerald-500">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                                    {{ $order->status }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1 font-bold">{{ $order->payment_method ?? 'N/A' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                                <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-icons text-4xl text-slate-300">receipt</span>
                                </div>
                                No hay transacciones registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection