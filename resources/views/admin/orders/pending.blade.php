<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-display py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase flex items-center gap-3">
                        <span class="material-icons text-[#FF6600] text-4xl">payments</span>
                        Taquilla <span class="text-[#FF6600]">Virtual</span>
                    </h1>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mt-1">Verificación de Pagos Pendientes</p>
                </div>
                
                <div class="flex gap-3">
                    <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Por Verificar</p>
                            <p class="text-xl font-black text-slate-900">{{ $orders->count() }}</p>
                        </div>
                        <span class="material-icons text-[#FF6600] opacity-20 text-4xl">pending_actions</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Orden</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Cliente</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Monto</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Referencia / Método</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Fecha</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-5">
                                    <span class="text-sm font-black text-slate-900">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-sm">{{ $order->user->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $order->user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-lg font-black text-[#FF6600]">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-slate-200">
                                            {{ str_replace('_', ' ', $order->payment_method) }}
                                        </span>
                                        <span class="text-sm font-mono font-bold text-slate-900 bg-yellow-50 px-2 py-1 rounded border border-yellow-100">
                                            REF: {{ $order->payment_reference }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-xs text-slate-500 font-bold">{{ $order->created_at->format('d/m/Y') }}</span><br>
                                    <span class="text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-xl transition-all shadow-lg shadow-emerald-200 group-hover:scale-110" title="Aprobar Pago">
                                                <span class="material-icons">check_circle</span>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-xl transition-all shadow-lg shadow-rose-200 group-hover:scale-110" title="Rechazar Pago">
                                                <span class="material-icons">cancel</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <span class="material-icons text-slate-200 text-6xl mb-4">task_alt</span>
                                    <p class="text-slate-400 font-bold uppercase tracking-[0.2em]">¡Felicidades! No hay pagos pendientes</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(session('success'))
                <div class="fixed bottom-10 right-10 bg-emerald-900 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-3 animate-bounce">
                    <span class="material-icons text-emerald-400">verified</span>
                    <span class="font-bold text-sm uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>