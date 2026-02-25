<nav class="sticky top-0 z-50 w-full bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 backdrop-blur-md">
    <div class="container mx-auto px-4 h-20 flex items-center justify-between">
        
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-[#FF6B00] rounded-xl flex items-center justify-center text-white shadow-lg">
                <span class="material-icons">confirmation_number</span>
            </div>
            <span class="text-2xl font-bold text-slate-900 dark:text-white">
                Misu<span class="text-[#FF6B00]">Tickets</span>
            </span>
        </div>

        <div class="relative hidden lg:block">
            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
            <input type="text" placeholder="Buscar eventos..." class="w-64 pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-full text-sm focus:ring-2 focus:ring-[#FF6B00]">
        </div>

        <div class="flex items-center gap-4">
            
            <button class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg text-[#FF6B00]">
                <span class="material-icons">light_mode</span>
            </button>

            <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-700 mx-1"></div>

            @guest
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-[#FF6B00]">INGRESAR</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-[#FF6B00] text-white text-xs font-extrabold rounded-full shadow-lg uppercase tracking-wider">
                        Registrarse
                    </a>
                </div>
            @endguest

            @auth
                @php
                    $dashboardUrl = Auth::user()->hasRole(['SuperAdmin', 'Organizador']) ? route('admin.dashboard') : route('home');
                @endphp
                
                <div class="flex items-center gap-3 bg-slate-100 dark:bg-slate-800 p-1 rounded-full pr-4">
                    <a href="{{ $dashboardUrl }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-[#FF6B00] rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-[#FF6B00]">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center ml-2 border-l pl-2 border-slate-300">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-500 flex items-center">
                            <span class="material-icons text-xl">logout</span>
                        </button>
                    </form>
                </div>
            @endauth

        </div>
    </div>
</nav>