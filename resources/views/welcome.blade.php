<!DOCTYPE html>
<html class="light" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MisuTicket - Entradas para los mejores eventos en Venezuela</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
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
                        "background-light": "#ffffff",
                        "background-dark": "#1a1a1a",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ff6b00; border-radius: 10px; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased font-display">
    
    <x-navbar />
    
    <main>
       <section class="relative w-full h-[550px] lg:h-[650px] overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent z-10"></div>
            
            <img alt="Público en concierto" class="absolute inset-0 w-full h-full object-cover object-center opacity-60" 
                 src="https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=2000&q=80" />
            
            <div class="container mx-auto px-4 relative z-20 h-full flex flex-col justify-center max-w-4xl">
                <span class="inline-block px-3 py-1 bg-[#FF6600] text-white text-xs font-black rounded mb-4 uppercase tracking-widest w-max shadow-md">
                    MiSUTiCKETS Oficial
                </span>
                
                <h1 class="text-5xl lg:text-7xl font-black text-white leading-tight mb-6">
                    Tu entrada a las <br /> <span class="text-[#FF6600]">Mejores Experiencias</span>
                </h1>
                
                <p class="text-lg text-slate-300 mb-8 max-w-xl font-medium leading-relaxed">
                    Descubre los eventos más esperados del país. Conciertos, teatro, deportes y festivales. Compra tus entradas de forma rápida, 100% segura y sin filas.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('events.index') }}" class="px-8 py-4 bg-[#FF6600] text-white font-black rounded-xl hover:bg-white hover:text-slate-900 transition-all flex items-center gap-2 group shadow-lg shadow-[#FF6600]/30 uppercase tracking-widest text-sm">
                        Explorar Cartelera
                        <span class="material-icons transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                    
                    @guest
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-xl border border-white/20 hover:bg-white/20 transition-all uppercase tracking-widest text-sm">
                        Crear Cuenta Libre
                    </a>
                    @endguest
                </div>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2 tracking-tight">Eventos Destacados 2026</h2>
                        <p class="text-slate-500">Lo más buscado en Venezuela esta temporada</p>
                    </div>
                    <a class="text-primary font-bold flex items-center gap-1 group text-sm uppercase tracking-wider" href="{{ route('events.index') }}">
                        Ver todos
                        <span class="material-icons text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($featuredEvents as $event)
                        <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-100 hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-300 flex flex-col">
                            <div class="aspect-video relative overflow-hidden bg-slate-100">
                                <img alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                     src="{{ $event->image_path ? asset('storage/' . $event->image_path) : 'https://placehold.co/600x400/0f172a/FFF?text=MiSUTiCKETS' }}" />
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-4 py-1.5 rounded-full text-primary font-bold text-sm shadow-sm">
                                    {{ $event->category ? $event->category->name : 'General' }}
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div class="flex gap-4 mb-6">
                                    <div class="flex flex-col items-center justify-center bg-primary/5 rounded-xl px-4 py-2 min-w-[65px] border border-primary/10">
                                        <span class="text-xs uppercase text-primary font-bold">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                                        <span class="text-2xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900 mb-1 group-hover:text-primary transition-colors">{{ $event->title }}</h3>
                                        <p class="text-sm text-slate-500 flex items-center gap-1">
                                            <span class="material-icons text-base text-slate-400">location_on</span> {{ $event->venue ? $event->venue->name : 'Por definir' }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('events.show', $event->id) }}" class="block text-center w-full py-3.5 bg-slate-900 hover:bg-primary text-white font-bold rounded-xl transition-all shadow-md active:scale-[0.98]">
                                    Seleccionar Entradas
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <span class="material-icons text-6xl text-slate-300 mb-4">event_busy</span>
                            <h3 class="text-xl font-bold text-slate-800">Estamos preparando nuevos eventos</h3>
                            <p class="text-slate-500 mt-2">Mantente atento a nuestras redes sociales para los próximos anuncios de MiSUTiCKETS.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="py-24 relative overflow-hidden bg-primary">
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-black rounded-full blur-[100px] translate-x-1/2 translate-y-1/2"></div>
            </div>
            <div class="container mx-auto px-4 relative z-10 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">¡No te pierdas de nada en 2026!</h2>
                <p class="text-white/90 max-w-lg mx-auto mb-10 text-lg">Suscríbete para recibir notificaciones exclusivas de tus artistas favoritos y acceso prioritario a preventas de la nueva temporada.</p>
                <form class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                    <input class="flex-grow px-7 py-5 rounded-2xl border-none focus:ring-4 focus:ring-black/10 text-slate-900 text-lg shadow-xl shadow-black/5" placeholder="Tu correo electrónico" type="email" />
                    <button class="px-10 py-5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-black/20 hover:scale-[1.02] active:scale-[0.98]" type="button">
                        Suscribirse Gratis
                    </button>
                </form>
            </div>
        </section>
    </main>
    
    <x-footer />

    <script>
        const themeBtn = document.querySelector('button:has(.material-icons:contains("light_mode"))') || document.querySelectorAll('button')[0]; 

        themeBtn?.addEventListener('click', () => {
            const html = document.documentElement;
            if (html.classList.contains('light')) {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            }
        });

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        }
    </script>
</body>
</html>