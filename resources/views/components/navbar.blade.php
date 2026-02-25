<nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <a href="/" class="font-black text-xl text-blue-600">MiSUTICKETS</a>

        <div class="flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400">Ingresar</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Registrarse</a>
            @endguest

            @auth
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold dark:text-white">
                        <i class="material-icons text-base align-middle">account_circle</i> 
                        {{ Auth::user()->name }}
                    </span>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 hover:underline">Cerrar Sesión</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>