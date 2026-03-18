<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title') @yield('title') | @endif {{ config('app.name', 'MiSUTiCKETS') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#FF6600" },
                    fontFamily: { display: ["Be Vietnam Pro", "sans-serif"] },
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 font-display antialiased flex h-screen overflow-hidden">

    <nav class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 shadow-2xl z-20">
        
        <div class="p-6 border-b border-slate-800">
            <h1 class="text-2xl font-black text-white tracking-tighter uppercase">
                MISU<span class="text-[#FF6600]">TICKETS</span>
            </h1>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-1">Admin Panel</p>
        </div>
        
       <ul class="mt-6 flex-1 px-4 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 text-emerald-500 hover:bg-slate-800 font-bold border border-slate-800/50 mb-4 bg-slate-800/30">
                <span class="material-icons text-lg">storefront</span>
                <span class="text-xs uppercase tracking-widest">Ir a Tienda</span>
            </a>

            @php $isDashboard = request()->is('admin/dashboard'); @endphp
            <li>
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 group {{ $isDashboard ? 'bg-[#FF6600]/10 text-[#FF6600]' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="material-icons text-lg {{ $isDashboard ? 'text-[#FF6600]' : 'text-slate-500 group-hover:text-white transition-colors' }}">speed</span>
                    <span class="text-xs uppercase tracking-widest font-bold {{ $isDashboard ? 'text-[#FF6600]' : 'text-slate-400 group-hover:text-white' }}">Dashboard</span>
                </a>
            </li>

            @php $isEvents = request()->routeIs('admin.events.*'); @endphp
            <li>
                <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 group {{ $isEvents ? 'bg-[#FF6600]/10 text-[#FF6600]' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="material-icons text-lg {{ $isEvents ? 'text-[#FF6600]' : 'text-slate-500 group-hover:text-white transition-colors' }}">event</span>
                    <span class="text-xs uppercase tracking-widest font-bold {{ $isEvents ? 'text-[#FF6600]' : 'text-slate-400 group-hover:text-white' }}">Eventos</span>
                </a>
            </li>

            {{-- Recintos (Venues) --}}
            @php $isVenues = request()->routeIs('admin.venues.*'); @endphp
            <li>
                <a href="{{ route('admin.venues.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 group {{ $isVenues ? 'bg-[#FF6600]/10 text-[#FF6600]' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="material-icons text-lg {{ $isVenues ? 'text-[#FF6600]' : 'text-slate-500 group-hover:text-white transition-colors' }}">stadium</span>
                    <span class="text-xs uppercase tracking-widest font-bold {{ $isVenues ? 'text-[#FF6600]' : 'text-slate-400 group-hover:text-white' }}">Recintos</span>
                </a>
            </li>

            {{-- Categorías --}}
            @php $isCategories = request()->routeIs('admin.categories.*'); @endphp
            <li>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 group {{ $isCategories ? 'bg-[#FF6600]/10 text-[#FF6600]' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="material-icons text-lg {{ $isCategories ? 'text-[#FF6600]' : 'text-slate-500 group-hover:text-white transition-colors' }}">sell</span>
                    <span class="text-xs uppercase tracking-widest font-bold {{ $isCategories ? 'text-[#FF6600]' : 'text-slate-400 group-hover:text-white' }}">Categorías</span>
                </a>
            </li>

            {{-- Taquilla Virtual (Pagos Pendientes) --}}
            @php $isOrders = request()->routeIs('admin.orders.*'); @endphp
            <li>
                <a href="{{ route('admin.orders.pending') }}" 
                   class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 group {{ $isOrders ? 'bg-[#FF6600]/10 text-[#FF6600]' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="material-icons text-lg {{ $isOrders ? 'text-[#FF6600]' : 'text-slate-500 group-hover:text-white transition-colors' }}">payments</span>
                    <span class="text-xs uppercase tracking-widest font-bold {{ $isOrders ? 'text-[#FF6600]' : 'text-slate-400 group-hover:text-white' }}">Taquilla Virtual</span>
                </a>
            </li>

            <li>
                <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-200 hover:bg-slate-800 hover:text-white group">
                    <span class="material-icons text-lg text-slate-500 group-hover:text-white transition-colors">support_agent</span>
                    <span class="text-xs uppercase tracking-widest font-bold text-slate-400 group-hover:text-white">Soporte</span>
                </a>
            </li>

            {{-- Sección de Administración Avanzada (Solo SuperAdmin) --}}
            @role('SuperAdmin')
                <div class="mt-10 mb-4 px-6">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Configuración Pro</span>
                </div>

                <a href="{{ route('admin.users.index') }}" 
                class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-[#FF6600] text-white shadow-lg shadow-[#FF6600]/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="material-icons text-[20px]">shield</span>
                    <span class="text-[11px] font-black uppercase tracking-widest">Seguridad y Roles</span>
                </a>

                {{-- NUEVO BOTÓN DE AUDITORÍA --}}
                @php $isAudits = request()->routeIs('admin.audits.*'); @endphp
                <a href="{{ route('admin.audits.index') }}" 
                class="flex items-center gap-3 px-6 py-4 mt-2 rounded-2xl transition-all duration-300 {{ $isAudits ? 'bg-[#FF6600] text-white shadow-lg shadow-[#FF6600]/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="material-icons text-[20px]">policy</span>
                    <span class="text-[11px] font-black uppercase tracking-widest">Auditoría</span>
                </a>
            @endrole
        </ul>

        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 py-3 px-4 rounded-2xl text-red-400 hover:bg-red-500/10 hover:text-red-300 font-bold transition duration-200">
                    <span class="material-icons text-lg">logout</span>
                    <span class="text-xs uppercase tracking-widest">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 overflow-y-auto p-6 md:p-10 relative">
        @yield('content')
    </main>

</body>

</html>