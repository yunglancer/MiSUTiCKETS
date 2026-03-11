<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-display py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ url()->previous() }}" class="text-slate-500 hover:text-[#FF6600] text-[10px] font-black uppercase tracking-widest flex items-center gap-1 transition-colors w-max">
                    <span class="material-icons text-sm">arrow_back</span> Volver al evento
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase mt-4 flex items-center gap-3">
                    <span class="material-icons text-[#FF6600] text-4xl">local_activity</span>
                    Selecciona tus <span class="text-[#FF6600]">Entradas</span>
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-slate-900/20">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#FF6600] rounded-full opacity-20 blur-3xl"></div>
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
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8">
                        
                        <form action="{{ route('checkout.summary') }}" method="POST" id="checkout-form">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="space-y-3 mb-8">
                                @foreach($event->eventZones as $zone)
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-2xl bg-white hover:border-[#FF6600] transition-colors shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800 text-lg">{{ $zone->venueZone->name ?? 'Zona General' }}</p>
                                        <p class="text-[#FF6600] font-black text-xl">${{ number_format($zone->price, 2) }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Límite: Máx 10 por usuario</p>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        <button type="button" class="w-8 h-8 rounded-full bg-white shadow text-slate-600 flex items-center justify-center font-bold hover:text-[#FF6600] transition-colors btn-minus" data-target="zone_{{ $zone->id }}">-</button>
                                        
                                        <input type="number" name="tickets[{{ $zone->id }}]" id="zone_{{ $zone->id }}" 
                                               class="w-8 text-center font-black text-slate-900 border-none bg-transparent p-0 focus:ring-0 zone-quantity" 
                                               value="0" min="0" max="{{ min(10, $zone->capacity) }}" readonly>
                                        
                                        <button type="button" class="w-8 h-8 rounded-full bg-white shadow text-slate-600 flex items-center justify-center font-bold hover:text-[#FF6600] transition-colors btn-plus" data-target="zone_{{ $zone->id }}">+</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <button type="submit" id="submit_btn" disabled class="w-full bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                Continuar al Resumen <span class="material-icons text-sm">arrow_forward</span>
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.zone-quantity');
            const btnsPlus = document.querySelectorAll('.btn-plus');
            const btnsMinus = document.querySelectorAll('.btn-minus');
            const submitBtn = document.getElementById('submit_btn');

            function checkTotals() {
                let totalTickets = 0;
                quantityInputs.forEach(input => {
                    totalTickets += (parseInt(input.value) || 0);
                });
                // Solo habilita el botón si seleccionó al menos 1 entrada
                submitBtn.disabled = (totalTickets === 0);
            }

            btnsPlus.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = document.getElementById(this.getAttribute('data-target'));
                    const max = parseInt(input.getAttribute('max'));
                    let val = parseInt(input.value) || 0;
                    if (val < max) { input.value = val + 1; checkTotals(); }
                });
            });

            btnsMinus.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = document.getElementById(this.getAttribute('data-target'));
                    let val = parseInt(input.value) || 0;
                    if (val > 0) { input.value = val - 1; checkTotals(); }
                });
            });
        });
    </script>
</x-app-layout>