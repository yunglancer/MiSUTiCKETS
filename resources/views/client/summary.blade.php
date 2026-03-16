<x-app-layout>
    @php
        $rate = $bcvRate ?? 60.50; 
        $totalBs = $grandTotal * $rate;
    @endphp

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

            {{-- Alerta General de Errores del Servidor --}}
            @if(session('error') || $errors->any())
                <div class="mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-2xl flex items-center gap-3 shadow-lg shadow-rose-200/50">
                        <span class="material-icons text-rose-500">error_outline</span>
                        <div>
                            <p class="text-rose-900 font-black text-[10px] uppercase tracking-[0.2em]">¡Acceso Restringido!</p>
                            <p class="text-rose-700 text-xs font-bold">Por favor, revisa los campos marcados en rojo abajo.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- COLUMNA IZQUIERDA: RESUMEN --}}
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
                            @forelse($cartItems ?? [] as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-slate-100">{{ $item['name'] ?? 'Entrada' }}</p>
                                        <p class="text-xs text-slate-400">{{ $item['quantity'] ?? 0 }} x REF. {{ number_format($item['price'] ?? 0, 2) }}</p>
                                    </div>
                                    <p class="font-black text-[#FF6600]">REF. {{ number_format($item['total'] ?? 0, 2) }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">Carrito vacío</p>
                            @endforelse
                        </div>

                        <div class="bg-slate-800/50 rounded-2xl p-5 border border-slate-700/50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-slate-400 font-bold uppercase tracking-wider">Subtotal</span>
                                <span class="font-bold text-slate-200">REF. {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-700">
                                <span class="text-sm text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1">
                                    Fee de Servicio <span class="material-icons text-[14px] text-slate-500">info</span>
                                </span>
                                <span class="font-bold text-slate-200">REF. {{ number_format($platformFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs text-[#FF6600] font-black uppercase tracking-widest">Total a Pagar</span>
                                <span class="text-3xl font-black text-white">REF. {{ number_format($grandTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-700/50">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Equivalente BCV (Bs. {{ number_format($rate, 2) }})</span>
                                <span class="text-lg font-black text-emerald-400">Bs. {{ number_format($totalBs, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: FORMULARIO --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg p-8">
                        
                        <form action="{{ route('checkout.process') }}" method="POST" id="payment-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <input type="hidden" name="cart_items" value="{{ base64_encode(json_encode($cartItems)) }}">
                            <input type="hidden" name="payment_method" id="selected_payment_method" value="{{ old('payment_method') }}" required>

                            <h3 class="text-sm font-black text-slate-800 uppercase mb-4 flex items-center gap-2">
                                <span class="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                ¿Cómo deseas pagar?
                            </h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                                <button type="button" data-method="pago_movil" class="payment-btn flex flex-col items-center justify-center p-4 rounded-2xl border-2 {{ old('payment_method') == 'pago_movil' ? 'selected border-[#FF6600] bg-[#FF6600]/5' : 'border-slate-200 bg-slate-50' }} hover:border-[#FF6600] transition-all relative group">
                                    <span class="material-icons text-3xl mb-2 text-slate-400 group-[.selected]:text-[#FF6600]">phone_android</span>
                                    <span class="text-[10px] font-black text-slate-600 group-[.selected]:text-[#FF6600] uppercase">Pago Móvil</span>
                                </button>
                                <button type="button" data-method="zelle" class="payment-btn flex flex-col items-center justify-center p-4 rounded-2xl border-2 {{ old('payment_method') == 'zelle' ? 'selected border-[#FF6600] bg-[#FF6600]/5' : 'border-slate-200 bg-slate-50' }} hover:border-[#FF6600] transition-all relative group">
                                    <span class="material-icons text-3xl mb-2 text-slate-400 group-[.selected]:text-[#FF6600]">attach_money</span>
                                    <span class="text-[10px] font-black text-slate-600 group-[.selected]:text-[#FF6600] uppercase">Zelle</span>
                                </button>
                                <button type="button" data-method="binance" class="payment-btn flex flex-col items-center justify-center p-4 rounded-2xl border-2 {{ old('payment_method') == 'binance' ? 'selected border-[#FF6600] bg-[#FF6600]/5' : 'border-slate-200 bg-slate-50' }} hover:border-[#FF6600] transition-all relative group">
                                    <span class="material-icons text-3xl mb-2 text-slate-400 group-[.selected]:text-[#FF6600]">currency_bitcoin</span>
                                    <span class="text-[10px] font-black text-slate-600 group-[.selected]:text-[#FF6600] uppercase">Binance Pay</span>
                                </button>
                            </div>

                            <div id="payment_instructions" class="{{ old('payment_method') ? '' : 'hidden opacity-0' }} transition-all duration-500 mb-8">
                                
                                {{-- INFO PAGO MOVIL --}}
                                <div id="info_pago_movil" class="bg-slate-50 rounded-2xl p-6 border border-slate-200 {{ old('payment_method') == 'pago_movil' ? '' : 'hidden' }} payment-info-box mb-6">
                                    <h4 class="text-[10px] font-black text-[#FF6600] uppercase tracking-widest mb-4">Realiza tu Pago Móvil a estos datos</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                        <div><p class="text-[10px] font-bold text-slate-400 uppercase">Banco</p><p class="font-bold text-slate-800">0102 - Banco de Venezuela</p></div>
                                        <div><p class="text-[10px] font-bold text-slate-400 uppercase">Teléfono</p><p class="font-bold text-slate-800">0412-1234567</p></div>
                                        <div><p class="text-[10px] font-bold text-slate-400 uppercase">Cédula / RIF</p><p class="font-bold text-slate-800">J-50000000-1</p></div>
                                    </div>
                                    <div class="bg-emerald-100 border border-emerald-200 rounded-xl p-3 text-center">
                                        <p class="text-[10px] font-black text-emerald-600 uppercase mb-1">Total Exacto a Transferir</p>
                                        <p class="text-xl font-black text-emerald-800">Bs. {{ number_format($totalBs, 2) }}</p>
                                    </div>
                                </div>

                                {{-- INFO ZELLE --}}
                                <div id="info_zelle" class="bg-slate-50 rounded-2xl p-6 border border-slate-200 {{ old('payment_method') == 'zelle' ? '' : 'hidden' }} payment-info-box mb-6">
                                    <h4 class="text-[10px] font-black text-[#FF6600] uppercase mb-4">Envía tu Zelle a este correo</h4>
                                    <p class="font-bold text-slate-800 text-lg">pagos@misutickets.com</p>
                                </div>

                                {{-- INFO BINANCE --}}
                                <div id="info_binance" class="bg-slate-50 rounded-2xl p-6 border border-slate-200 {{ old('payment_method') == 'binance' ? '' : 'hidden' }} payment-info-box mb-6">
                                    <h4 class="text-[10px] font-black text-[#FF6600] uppercase mb-4">Envía a este Pay ID de Binance</h4>
                                    <p class="font-bold text-slate-800 text-lg">123456789</p>
                                    <p class="text-xs text-slate-500 mt-1">Solo USDT (Red TRC20 o Interno).</p>
                                </div>

                                {{-- DATOS EXTRAS (Con Errores Estéticos) --}}
                                <div id="extra_payment_data" class="space-y-4 mb-6 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                                    <h4 class="text-[10px] font-black text-slate-800 uppercase flex items-center gap-2 mb-2">
                                        <span class="material-icons text-sm text-[#FF6600]">person_pin</span> Datos de la Transferencia
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label id="label_name" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nombre del Titular</label>
                                            <input type="text" name="payment_name" id="payment_name" value="{{ old('payment_name') }}" class="w-full px-4 py-3 bg-white border {{ $errors->has('payment_name') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-200' }} rounded-xl text-sm font-bold transition-all outline-none">
                                            @error('payment_name') 
                                                <p class="text-rose-500 text-[10px] font-bold mt-1 flex items-center gap-1"><span class="material-icons text-[12px]">error</span>{{ $message }}</p> 
                                            @enderror
                                            <p id="error-payment_name" class="text-rose-500 text-[10px] font-bold mt-1 hidden flex items-center gap-1"><span class="material-icons text-[12px]">error</span><span class="err-msg"></span></p>
                                        </div>
                                        <div>
                                            <label id="label_document" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Documento</label>
                                            <input type="text" name="payment_document" id="payment_document" value="{{ old('payment_document') }}" class="w-full px-4 py-3 bg-white border {{ $errors->has('payment_document') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-200' }} rounded-xl text-sm font-bold transition-all outline-none">
                                            @error('payment_document') 
                                                <p class="text-rose-500 text-[10px] font-bold mt-1 flex items-center gap-1"><span class="material-icons text-[12px]">error</span>{{ $message }}</p> 
                                            @enderror
                                            <p id="error-payment_document" class="text-rose-500 text-[10px] font-bold mt-1 hidden flex items-center gap-1"><span class="material-icons text-[12px]">error</span><span class="err-msg"></span></p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label id="label_phone" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Teléfono</label>
                                            <input type="text" name="payment_phone" id="payment_phone" value="{{ old('payment_phone') }}" class="w-full px-4 py-3 bg-white border {{ $errors->has('payment_phone') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-200' }} rounded-xl text-sm font-bold transition-all outline-none">
                                            @error('payment_phone') 
                                                <p class="text-rose-500 text-[10px] font-bold mt-1 flex items-center gap-1"><span class="material-icons text-[12px]">error</span>{{ $message }}</p> 
                                            @enderror
                                            <p id="error-payment_phone" class="text-rose-500 text-[10px] font-bold mt-1 hidden flex items-center gap-1"><span class="material-icons text-[12px]">error</span><span class="err-msg"></span></p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Capture del Pago (Requerido)</label>
                                        <div class="relative flex items-center justify-center w-full">
                                            <label for="payment_receipt_path" class="flex flex-col items-center justify-center w-full h-32 border-2 {{ $errors->has('payment_receipt_path') ? 'border-rose-500 ring-1 ring-rose-500 bg-rose-50' : 'border-slate-300 bg-white' }} border-dashed rounded-xl cursor-pointer hover:bg-[#FF6600]/5 transition-all group text-center">
                                                <span class="material-icons text-3xl text-slate-400 group-hover:text-[#FF6600] mb-2">cloud_upload</span>
                                                <p id="file-name-text" class="text-xs text-slate-500 font-bold">Haz clic para subir tu capture</p>
                                                <input id="payment_receipt_path" name="payment_receipt_path" type="file" class="hidden" accept="image/*" />
                                            </label>
                                        </div>
                                        <p id="file-name-display-box" class="text-xs font-black text-emerald-600 mt-2 text-center hidden">
                                            <span class="material-icons text-[14px] align-middle">check_circle</span> <span id="file-name-text-success"></span>
                                        </p>
                                        @error('payment_receipt_path') 
                                            <p class="text-rose-500 text-[10px] font-bold mt-1 flex items-center justify-center gap-1"><span class="material-icons text-[12px]">error</span>{{ $message }}</p> 
                                        @enderror
                                        <p id="error-payment_receipt_path" class="text-rose-500 text-[10px] font-bold mt-1 hidden flex items-center justify-center gap-1"><span class="material-icons text-[12px]">error</span><span class="err-msg"></span></p>
                                    </div>
                                </div>

                                {{-- REFERENCIA --}}
                                <div>
                                    <label class="block text-[10px] font-black text-slate-800 uppercase mb-2 ml-1 text-center">Número de Referencia</label>
                                    <input type="text" name="payment_reference" id="reference_input" value="{{ old('payment_reference') }}" required placeholder="Solo números" class="w-full px-5 py-4 bg-slate-50 border {{ $errors->has('payment_reference') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-slate-200' }} rounded-2xl text-center text-lg font-black outline-none">
                                    @error('payment_reference') 
                                        <p class="text-rose-500 text-[10px] font-bold mt-1 flex items-center justify-center gap-1"><span class="material-icons text-[12px]">error</span>{{ $message }}</p> 
                                    @enderror
                                    <p id="error-reference_input" class="text-rose-500 text-[10px] font-bold mt-1 hidden flex items-center justify-center gap-1"><span class="material-icons text-[12px]">error</span><span class="err-msg"></span></p>
                                </div>
                            </div>

                            <button type="submit" id="submit_btn" {{ old('payment_method') ? '' : 'disabled' }} class="w-full bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-widest transition-all shadow-lg shadow-[#FF6600]/30 disabled:opacity-50">
                                Confirmar y Subir Pago
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const btns = document.querySelectorAll('.payment-btn');
            const methodInput = document.getElementById('selected_payment_method');
            const instructions = document.getElementById('payment_instructions');
            const infoBoxes = document.querySelectorAll('.payment-info-box');
            const submitBtn = document.getElementById('submit_btn');
            const fileInput = document.getElementById('payment_receipt_path');
            const fileNameDisplayBox = document.getElementById('file-name-display-box');
            const fileNameTextSuccess = document.getElementById('file-name-text-success');

            // Actualizar nombre de archivo
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    fileNameTextSuccess.textContent = "Archivo: " + this.files[0].name;
                    fileNameDisplayBox.classList.remove('hidden');
                    const errBox = document.getElementById('error-payment_receipt_path');
                    if(errBox) errBox.classList.add('hidden');
                }
            });

            // Selección de método
            btns.forEach(btn => {
                btn.addEventListener('click', function() {
                    btns.forEach(b => {
                        b.classList.remove('selected', 'border-[#FF6600]', 'bg-[#FF6600]/5');
                        b.classList.add('border-slate-200');
                    });
                    this.classList.add('selected', 'border-[#FF6600]', 'bg-[#FF6600]/5');
                    
                    const method = this.dataset.method;
                    methodInput.value = method;

                    infoBoxes.forEach(box => box.classList.add('hidden'));
                    document.getElementById('info_' + method).classList.remove('hidden');

                    instructions.classList.remove('hidden');
                    setTimeout(() => instructions.classList.remove('opacity-0'), 50);
                    submitBtn.disabled = false;
                });
            });

            // VALIDACIÓN FRONT-END Inteligente
            form.addEventListener('submit', function(e) {
                let hasErrors = false;
                const method = methodInput.value;
                
                // Limpiar errores visuales
                document.querySelectorAll('[id^="error-"]').forEach(p => p.classList.add('hidden'));
                document.querySelectorAll('input').forEach(i => i.classList.remove('border-rose-500', 'ring-1', 'ring-rose-500'));

                const fields = [
                    { id: 'payment_name', label: 'Nombre' },
                    { id: 'payment_document', label: 'Documento' },
                    { id: 'payment_phone', label: 'Teléfono' },
                    { id: 'reference_input', label: 'Referencia' }
                ];

                // Check empty fields
                fields.forEach(f => {
                    const input = document.getElementById(f.id);
                    if (!input.value.trim()) {
                        showError(f.id, `El campo es obligatorio.`);
                        hasErrors = true;
                    }
                });

                // Check file
                if (!fileInput.files.length) {
                    showError('payment_receipt_path', 'Debes subir el capture.');
                    hasErrors = true;
                }

                // Check Regex Name
                const nameVal = document.getElementById('payment_name').value.trim();
                if (nameVal && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nameVal)) {
                    showError('payment_name', 'Solo se permiten letras y espacios.');
                    hasErrors = true;
                }

                // Check Document Inteligente
                const docVal = document.getElementById('payment_document').value.trim();
                if (docVal) {
                    if (method === 'pago_movil') {
                        if (!/\d/.test(docVal)) {
                            showError('payment_document', 'La cédula debe contener números.');
                            hasErrors = true;
                        }
                    } else if (method === 'zelle') {
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(docVal)) {
                            showError('payment_document', 'Debe ser un correo electrónico válido.');
                            hasErrors = true;
                        }
                    } else if (method === 'binance') {
                        if (!/^\d+$/.test(docVal) && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(docVal)) {
                            showError('payment_document', 'Debe ser un Pay ID numérico o correo.');
                            hasErrors = true;
                        }
                    }
                }

                // Check Phone
                const phoneVal = document.getElementById('payment_phone').value.trim();
                if (phoneVal) {
                    if (!/^[\d\+\-\s]+$/.test(phoneVal)) {
                        showError('payment_phone', 'Solo números y signo +.');
                        hasErrors = true;
                    } else if (phoneVal.replace(/\D/g, '').length < 10) {
                        showError('payment_phone', 'Mínimo 10 dígitos.');
                        hasErrors = true;
                    }
                }

                // Check Regex Ref
                const refVal = document.getElementById('reference_input').value.trim();
                if (refVal && !/^\d+$/.test(refVal)) {
                    showError('reference_input', 'La referencia debe contener solo números.');
                    hasErrors = true;
                }

                if (hasErrors) {
                    e.preventDefault();
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="material-icons animate-spin text-sm">sync</span> Procesando...';
            });

            // Función visual limpia con icono Material
            function showError(id, msg) {
                const p = document.getElementById('error-' + id);
                const input = document.getElementById(id);
                if (p) { 
                    const span = p.querySelector('.err-msg');
                    if(span) span.textContent = msg;
                    p.classList.remove('hidden'); 
                }
                if (input && input.type !== 'file') {
                    input.classList.add('border-rose-500', 'ring-1', 'ring-rose-500');
                }
            }
        });
    </script>
</x-app-layout>