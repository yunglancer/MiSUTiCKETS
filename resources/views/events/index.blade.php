<!DOCTYPE html>
<html class="light" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Cartelera - MisuTicket</title>
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
                    }
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ff6b00;
            border-radius: 10px;
        }

        /* Punto 2: Estilo Hover para botones de filtro */
        .btn-filter-custom:hover {
            background-color: #ff6b00 !important;
            color: white !important;
            border-color: #ff6b00 !important;
        }

        /* Punto 3: Naranja característico para el buscador */
        .search-custom-focus:focus {
            border-color: #ff6b00 !important;
            --tw-ring-color: rgba(255, 107, 0, 0.2) !important;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased font-display custom-scrollbar">
    
    <x-navbar />

    <main class="min-h-screen">
        <section class="relative w-full h-[500px] lg:h-[600px] overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/40 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1506157786151-b8491531f063?q=80&w=2070" 
                 alt="Fondo Concierto" 
                 class="absolute inset-0 w-full h-full object-cover z-0">
            
            <div class="container mx-auto px-4 relative z-20 h-full flex flex-col justify-center">
                <div class="max-w-4xl">
                    <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-bold rounded mb-4 uppercase tracking-widest">Temporada 2026</span>
                    <h1 class="text-5xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                        Cartelera de <br><span class="text-primary">Eventos</span>
                    </h1>
                    <p class="text-lg text-slate-200 max-w-xl mb-8">
                        Explora los mejores conciertos, obras de teatro y eventos deportivos en toda Venezuela con la mejor experiencia visual.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:shadow-xl hover:shadow-primary/40 transition-all flex items-center gap-2 group">
                            Explorar Ahora
                            <span class="material-icons transition-transform group-hover:translate-x-1">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-white dark:bg-background-dark">
            <div class="container mx-auto px-4">
                
                <div class="flex flex-col lg:flex-row gap-6 mb-20 justify-between items-center">
                    <div class="w-full lg:w-1/2 relative flex items-center group">
                        <span class="material-icons absolute left-5 text-slate-400 group-focus-within:text-primary transition-colors" style="font-size: 20px;">search</span>
                        <input type="text" placeholder="¿Qué evento buscas? (Ej: Concierto de Rock...)" 
                               class="search-custom-focus w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-full focus:ring-4 text-slate-900 dark:text-white shadow-inner transition-all">
                    </div>

                    <div class="flex gap-3 overflow-x-auto pb-2 w-full lg:w-auto">
                        <button class="px-7 py-3.5 bg-slate-900 dark:bg-primary text-white font-bold rounded-full text-sm shadow-lg transition-transform active:scale-95">Todos</button>
                        <button class="btn-filter-custom px-7 py-3.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-full text-sm border border-slate-200 transition-all active:scale-95">Conciertos</button>
                        <button class="btn-filter-custom px-7 py-3.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-full text-sm border border-slate-200 transition-all active:scale-95">Teatro</button>
                        <button class="btn-filter-custom px-7 py-3.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-full text-sm border border-slate-200 transition-all active:scale-95">Deportes</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    
                    <div class="group flex flex-col h-full bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-300">
                        <div class="aspect-[16/10] relative overflow-hidden">
                            <img alt="Festival Verano" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=800" />
                            <div class="absolute top-5 right-5 bg-white/95 backdrop-blur-sm px-5 py-2 rounded-2xl text-primary font-bold text-sm shadow-sm">Desde $25</div>
                        </div>
                        <div class="p-8 flex-grow flex flex-col justify-between">
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-700 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-600 h-fit">
                                    <span class="text-[10px] font-bold text-primary uppercase">Mar</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">15</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors leading-tight">Festival de Verano 2026</h3>
                                    <div class="flex items-center text-slate-400 text-sm font-medium">
                                        <span class="material-icons text-slate-400 mr-2" style="font-size: 18px;">location_on</span> Estadio Principal, Maracay
                                    </div>
                                </div>
                            </div>
                            <button class="w-full mt-6 py-4 bg-slate-900 hover:bg-primary text-white font-bold rounded-2xl transition-all shadow-md active:scale-[0.98] uppercase tracking-widest text-xs">Seleccionar Entradas</button>
                        </div>
                    </div>

                    <div class="group flex flex-col h-full bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-300">
                        <div class="aspect-[16/10] relative overflow-hidden">
                            <img alt="Gala de Ballet" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=800" />
                            <div class="absolute top-5 right-5 bg-white/95 backdrop-blur-sm px-5 py-2 rounded-2xl text-primary font-bold text-sm shadow-sm">Desde $15</div>
                        </div>
                        <div class="p-8 flex-grow flex flex-col justify-between">
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-700 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-600 h-fit">
                                    <span class="text-[10px] font-bold text-primary uppercase">Mar</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">20</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors leading-tight">Gala de Ballet Clásico</h3>
                                    <div class="flex items-center text-slate-400 text-sm font-medium">
                                        <span class="material-icons text-slate-400 mr-2" style="font-size: 18px;">location_on</span> Teatro Teresa Carreño
                                    </div>
                                </div>
                            </div>
                            <button class="w-full mt-6 py-4 bg-slate-900 hover:bg-primary text-white font-bold rounded-2xl transition-all shadow-md active:scale-[0.98] uppercase tracking-widest text-xs">Seleccionar Entradas</button>
                        </div>
                    </div>

                    <div class="group flex flex-col h-full bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-300">
                        <div class="aspect-[16/10] relative overflow-hidden">
                            <img alt="Rock en vivo" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800" />
                            <div class="absolute top-5 right-5 bg-white/95 backdrop-blur-sm px-5 py-2 rounded-2xl text-primary font-bold text-sm shadow-sm">Desde $15</div>
                        </div>
                        <div class="p-8 flex-grow flex flex-col justify-between">
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-700 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-600 h-fit">
                                    <span class="text-[10px] font-bold text-primary uppercase">Mar</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">25</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors leading-tight">Rock en la Ciudad</h3>
                                    <div class="flex items-center text-slate-400 text-sm font-medium">
                                        <span class="material-icons text-slate-400 mr-2" style="font-size: 18px;">location_on</span> Plaza Bolívar
                                    </div>
                                </div>
                            </div>
                            <button class="w-full mt-6 py-4 bg-slate-900 hover:bg-primary text-white font-bold rounded-2xl transition-all shadow-md active:scale-[0.98] uppercase tracking-widest text-xs">Seleccionar Entradas</button>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <x-footer />
</body>
</html>