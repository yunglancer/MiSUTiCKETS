<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#ff6b00",
                    "background-dark": "#1a1a1a",
                },
                fontFamily: {
                    "display": ["Be Vietnam Pro"]
                },
            },
        },
    }
</script>

<style type="text/tailwindcss">
    body { font-family: 'Be Vietnam Pro', sans-serif; }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>

<div class="bg-background-dark min-h-screen font-display text-white antialiased">
    
    <x-navbar />

    <div class="relative w-full h-[60vh] flex items-end overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/40 to-transparent z-10"></div>
        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070" 
             class="absolute inset-0 w-full h-full object-cover object-center scale-105" 
             alt="Banner Evento">
        
        <div class="relative container mx-auto px-8 pb-16 z-20">
            <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-bold rounded mb-4 uppercase tracking-widest">
                Destacado
            </span>
            <h1 class="text-6xl md:text-8xl font-bold tracking-tight mb-4 text-white">
                {{ $event->title }} <br> <span class="text-primary">FEST 2026</span>
            </h1>
            <p class="text-xl text-slate-200 font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">location_on</span> 
                {{ $event->location }} | <span class="material-symbols-outlined text-primary">calendar_today</span> {{ \Carbon\Carbon::parse($event->event_date)->format('d \d\e F') }}
            </p>
        </div>
    </div>

    <div class="container mx-auto px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            <div class="lg:col-span-2 space-y-10">
                <div class="border-l-4 border-primary pl-6">
                    <h2 class="text-3xl font-bold mb-4 tracking-tight uppercase text-white">Descripción del Evento</h2>
                    <p class="text-slate-400 text-lg leading-relaxed">
                        {{ $event->description }} a la <strong>Zona de Aislamiento (Misión 3)</strong>. Este diseño utiliza la identidad visual de <strong>MisuTickets</strong>. Aquí los usuarios podrán revisar los detalles antes de proceder al checkout seguro.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 backdrop-blur-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="material-symbols-outlined text-primary">schedule</span>
                            <p class="text-primary font-bold text-sm uppercase tracking-wider">Apertura</p>
                        </div>
                        <p class="text-2xl font-bold text-white">04:00 PM</p>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 backdrop-blur-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="material-symbols-outlined text-primary">family_restroom</span>
                            <p class="text-primary font-bold text-sm uppercase tracking-wider">Edad mínima</p>
                        </div>
                        <p class="text-2xl font-bold text-white">+18 años</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white/5 p-10 rounded-[2rem] border border-white/10 shadow-2xl sticky top-24 backdrop-blur-xl">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-2 italic">Entradas desde</p>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-5xl font-bold text-white">${{ number_format($event->base_price, 2) }}</span>
                        <span class="text-slate-500 text-sm">/ p.p.</span>
                    </div>
                    
                <a href="{{ route('checkout.show', $event->id) }}" 
                    class="w-full py-5 bg-primary hover:bg-[#e66000] text-white font-bold rounded-2xl transition-all transform hover:shadow-xl hover:shadow-primary/40 active:scale-95 flex items-center justify-center gap-3 text-lg group decoration-transparent">
                        Comprar Entradas
                    <span class="material-icons transition-transform group-hover:translate-x-1">local_activity</span>
                </a>
                    
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            Selección de asientos en tiempo real
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="material-symbols-outlined text-primary text-xl">qr_code_2</span>
                            Código QR inmediato al correo
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />
</div>