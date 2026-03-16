<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-display py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('checkout.show', $event->id) }}" class="text-slate-500 hover:text-[#FF6600] text-[10px] font-black uppercase tracking-widest flex items-center gap-1 transition-colors w-max">
                    <span class="material-icons text-sm">arrow_back</span> Modificar entradas
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase mt-4 flex items-center gap-3">
                    <span class="material-icons text-[#FF6600] text-4xl">receipt_long</span>
                    Resumen y <span class="text-[#FF6600]">Pago</span>
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-slate-900/20">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#FF6600] rounded-full opacity-20 blur-3xl"></div>
                        
                        <span class="bg-[#FF6600] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full inline-block mb-4">
                            Tu Orden
                        </span>
                        
                        <h2 class="text-2xl font-bold leading-tight mb-2">{{ $event->title }}</h2>
                        <p class="text-sm text-slate-300 mb-6 flex items-center gap-2">
                            <span class="material-icons text-[#FF6600] text-sm">calendar_today</span> 
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y - h:i A') }}
                        </p>

                        <div class="space-y-4 border-t border-slate-700 border-dashed pt-6 mb-6">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-slate-100">{{ $item['name'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $item['quantity'] }} x REF. {{ number_format($item['price'], 2) }}</p>
                                    </div>
                                    <p class="font-black text-[#FF6600]">REF. {{ number_format($item['total'], 2) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-slate-800/50 rounded-2xl p-5 border border-slate-700/50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-400 font-bold uppercase tracking-wider">Subtotal</span>
                                <span class="font-bold text-slate-200">REF. {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-700">
                                <span class="text-sm text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1">
                                    Fee de Servicio <span class="material-icons text-[14px] text-slate-500" title="10% por costos de plataforma">info</span>
                                </span>
                                <span class="font-bold text-slate-200">REF. {{ number_format($platformFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-end">
                                <span class="text-xs text-[#FF6600] font-black uppercase tracking-widest">Total a Pagar</span>
                                <span class="text-3xl font-black text-white">REF. {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8">
                        
                        <form action="{{ route('checkout.process') }}" method="POST" id="payment-form">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <input type="hidden" name="cart_items" value="{{ json_encode($cartItems) }}">
                            <input type="hidden" name="payment_method" id="selected_payment_method" required>

                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                ¿Cómo deseas pagar?
                            </h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                                <button type="button" data-method="pago_movil" class="payment-btn flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-200 bg-slate-50 hover:border-[#FF6600] transition-all cursor-pointer relative group">
                                    <span class="material-icons text-3xl mb-2 text-slate-400 group-[.selected]:text-[#FF6600] transition-colors">phone_android</span>
                                    <span class="text-[10px] font-black text-slate-600 group-[.selected]:text-[#FF6600] uppercase tracking-wider transition-colors">Pago Móvil</span>
                                </button>
                                <button type="button" data-method="zelle" class="payment-btn flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-200 bg-slate-50 hover:border-[#FF6600] transition-all cursor-pointer relative group">
                                    <span class="material-icons text-3xl mb-2 text-slate-400 group-[.selected]:text-[#FF6600] transition-colors">attach_money</span>
                                    <span class="text-[10px] font-black text-slate-600 group-[.selected]:text-[#FF6600] uppercase tracking-wider transition-colors">Zelle</span>
                                </button>
                                <button type="button" data-method="binance" class="payment-btn flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-slate-200 bg-slate-50 hover:border-[#FF6600] transition-all cursor-pointer relative group">
                                    <span class="material-icons text-3xl mb-2 text-slate-400 group-[.selected]:text-[#FF6600] transition-colors">currency_bitcoin</span>
                                    <span class="text-[10px] font-black text-slate-600 group-[.selected]:text-[#FF6600] uppercase tracking-wider transition-colors">Binance Pay</span>
                                </button>
                            </div>

                            <div id="payment_instructions" class="hidden opacity-0 transition-all duration-500 mb-8">
                                
                                <div id="info_pago_movil" class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hidden payment-info-box mb-6">
                                    <h4 class="text-[10px] font-black text-[#FF6600] uppercase tracking-widest mb-4">Realiza tu Pago Móvil a estos datos</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Banco</p><p class="font-bold text-slate-800">0102 - Banco de Venezuela</p></div>
                                        <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Teléfono</p><p class="font-bold text-slate-800">0412-1234567</p></div>
                                        <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cédula / RIF</p><p class="font-bold text-slate-800">J-50000000-1</p></div>
                                        </div>
                                </div>

                                <div id="info_zelle" class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hidden payment-info-box mb-6">
                                    <h4 class="text-[10px] font-black text-[#FF6600] uppercase tracking-widest mb-4">Envía tu Zelle a este correo</h4>
                                    <p class="font-bold text-slate-800 text-lg">pagos@misutickets.com</p>
                                </div>

                                <div id="info_binance" class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hidden payment-info-box mb-6">
                                    <h4 class="text-[10px] font-black text-[#FF6600] uppercase tracking-widest mb-4">Envía a este Pay ID de Binance</h4>
                                    <p class="font-bold text-slate-800 text-lg">123456789</p>
                                    <p class="text-xs text-slate-500 mt-1">Solo USDT a través de la red TRC20 o envío interno.</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Número de Referencia del Pago</label>
                                    <input type="text" name="payment_reference" id="reference_input" required placeholder="Ej: 849201" maxlength="15"
                                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 tracking-widest font-bold text-center text-lg transition-all outline-none">
                                </div>
                            </div>

                            <button type="submit" id="submit_btn" disabled class="w-full bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                Confirmar y Generar Entradas <span class="material-icons text-sm">check_circle</span>
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentBtns = document.querySelectorAll('.payment-btn');
            const selectedPaymentMethod = document.getElementById('selected_payment_method');
            const paymentInstructions = document.getElementById('payment_instructions');
            const infoBoxes = document.querySelectorAll('.payment-info-box');
            const submitBtn = document.getElementById('submit_btn');
            const referenceInput = document.getElementById('reference_input');

            paymentBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Limpiar selecciones previas
                    paymentBtns.forEach(b => {
                        b.classList.remove('selected', 'border-[#FF6600]', 'bg-[#FF6600]/5');
                        b.classList.add('border-slate-200');
                    });

                    // Marcar el actual
                    this.classList.add('selected', 'border-[#FF6600]', 'bg-[#FF6600]/5');
                    this.classList.remove('border-slate-200');

                    // Guardar valor en input oculto
                    const method = this.getAttribute('data-method');
                    selectedPaymentMethod.value = method;

                    // Ocultar todas las cajas de info y mostrar la correcta
                    infoBoxes.forEach(box => box.classList.add('hidden'));
                    document.getElementById('info_' + method).classList.remove('hidden');

                    // Revelar el contenedor de instrucciones y el botón
                    paymentInstructions.classList.remove('hidden');
                    setTimeout(() => paymentInstructions.classList.remove('opacity-0'), 50);
                    submitBtn.disabled = false;
                    
                    // Hacer foco en el input de referencia para acelerar la compra
                    setTimeout(() => referenceInput.focus(), 100);
                });
            });
        });
        document.querySelector('form').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Procesando búnker...';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });
    </script>
</x-app-layout>