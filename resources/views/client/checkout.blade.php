<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-display py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ url()->previous() }}" class="text-slate-500 hover:text-[#FF6600] text-[10px] font-black uppercase tracking-widest flex items-center gap-1 transition-colors w-max">
                    <span class="material-icons text-sm">arrow_back</span> Volver al evento
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase mt-4 flex items-center gap-3">
                    <span class="material-icons text-[#FF6600] text-4xl">shopping_cart_checkout</span>
                    Finalizar <span class="text-[#FF6600]">Compra</span>
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-slate-900/20">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#FF6600] rounded-full opacity-20 blur-3xl"></div>
                        
                        <span class="bg-[#FF6600] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full inline-block mb-4">
                            Resumen de Orden
                        </span>
                        
                        <h2 class="text-2xl font-bold leading-tight mb-2">{{ $event->title }}</h2>
                        <div class="space-y-3 mt-6">
                            <p class="flex items-center gap-3 text-sm text-slate-300">
                                <span class="material-icons text-[#FF6600]">calendar_today</span> 
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y - h:i A') }}
                            </p>
                            <p class="flex items-center gap-3 text-sm text-slate-300">
                                <span class="material-icons text-[#FF6600]">location_on</span> 
                                {{ $event->venue ? $event->venue->name : 'Por definir' }}
                            </p>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-700 border-dashed">
                            <p class="text-xs text-slate-400 mb-2">Titular de las entradas:</p>
                            <p class="font-bold text-lg">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-slate-400 mt-1">C.I: {{ Auth::user()->document_id }}</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8">
                        
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 mb-8">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="material-icons text-emerald-500">account_balance_wallet</span>
                                Datos para Pago Móvil
                            </h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Banco</p>
                                    <p class="font-bold text-slate-800">0102 - Banco de Venezuela</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Teléfono</p>
                                    <p class="font-bold text-slate-800">0412-1234567</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cédula / RIF</p>
                                    <p class="font-bold text-slate-800">J-50000000-1</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">A nombre de</p>
                                    <p class="font-bold text-slate-800">MiSUTiCKETS C.A.</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            
                            <input type="hidden" name="payment_method" value="Pago Móvil">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Cantidad de Entradas</label>
                                    <input type="number" name="quantity" min="1" max="10" value="1" required
                                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Monto Total Pagado (Bs)</label>
                                    <input type="number" step="0.01" name="total_amount" required placeholder="Ej: 550.00"
                                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Número de Referencia (Últimos 6 dígitos)</label>
                                <input type="text" name="payment_reference" required placeholder="Ej: 849201" maxlength="8"
                                       class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 tracking-widest font-bold text-center text-lg transition-all outline-none">
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <button type="submit" class="w-full bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2">
                                    Reportar Pago y Generar Entradas <span class="material-icons text-sm">confirmation_number</span>
                                </button>
                                <p class="text-center text-[10px] text-slate-400 mt-4 uppercase tracking-widest font-bold">
                                    Al hacer clic, tus entradas se generarán y quedarán pendientes de verificación.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>