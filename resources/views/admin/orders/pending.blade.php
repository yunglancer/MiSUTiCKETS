<x-app-layout>
    <div x-data="{ open: false, paymentData: {} }" class="min-h-screen bg-slate-50 font-display py-12">
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
                                        <span class="font-bold text-slate-800 text-sm">{{ $order->payment_name }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $order->payment_document }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-lg font-black text-[#FF6600]">REF. {{ number_format($order->total_amount, 2) }}</span>
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
                                        <button 
                                            @click="open = true; paymentData = { 
                                                img: '{{ $order->payment_receipt_path }}',
                                                doc: '{{ $order->payment_document }}',
                                                phone: '{{ $order->payment_phone }}',
                                                ref: '{{ $order->payment_reference }}',
                                                name: '{{ $order->payment_name }}'
                                            }"
                                            class="bg-slate-800 hover:bg-black text-white p-2 rounded-xl transition-all shadow-lg shadow-slate-200 group-hover:scale-110" 
                                            title="Ver Comprobante">
                                            <span class="material-icons">visibility</span>
                                        </button>

                                        <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de APROBAR este pago? Se enviarán los tickets al cliente.')">
                                            @csrf
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-xl transition-all shadow-lg shadow-emerald-200 group-hover:scale-110" title="Aprobar Pago">
                                                <span class="material-icons">check_circle</span>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" onsubmit="return confirm('¿Rechazar este pago? Se liberarán los tickets.')">
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

            <template x-teleport="body">
                <div x-show="open" 
                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-cloak>
                    
                    <div @click.away="open = false" class="bg-white rounded-[2rem] max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100">
                        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="font-black text-slate-900 uppercase tracking-tight text-lg">Verificación de <span class="text-[#FF6600]">Pago</span></h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Referencia: #<span x-text="paymentData.ref"></span></p>
                            </div>
                            <button @click="open = false" class="bg-white text-slate-400 hover:text-rose-500 p-2 rounded-full shadow-sm transition-colors flex items-center justify-center">
                                <span class="material-icons">close</span>
                            </button>
                        </div>

                        <div class="p-8">
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="space-y-1">
                                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Titular del Pago</span>
                                    <p x-text="paymentData.name" class="text-sm font-bold text-slate-800"></p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Documento / CI</span>
                                    <p x-text="paymentData.doc" class="text-sm font-bold text-slate-800"></p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Teléfono Contacto</span>
                                    <p x-text="paymentData.phone" class="text-sm font-bold text-slate-800"></p>
                                </div>
                            </div>

                            <div class="relative group">
                                <div class="absolute -top-3 left-6 bg-[#FF6600] text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg z-10">
                                    Capture de Pantalla
                                </div>
                                <div class="rounded-3xl border-4 border-slate-50 overflow-hidden shadow-inner bg-slate-100">
                                    <img :src="paymentData.img" class="w-full h-auto object-contain max-h-80 mx-auto" alt="Comprobante de pago">
                                </div>
                                <a :href="paymentData.img" target="_blank" class="mt-4 flex items-center justify-center gap-2 text-[10px] font-black text-slate-400 hover:text-[#FF6600] uppercase tracking-widest transition-colors">
                                    <span class="material-icons text-sm">open_in_new</span> Ver en tamaño completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            @if(session('success'))
                <div class="fixed bottom-10 right-10 bg-emerald-900 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-3 animate-bounce">
                    <span class="material-icons text-emerald-400">verified</span>
                    <span class="font-bold text-sm uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>