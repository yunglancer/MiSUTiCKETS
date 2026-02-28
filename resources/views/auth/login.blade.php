<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <title>Inicio de Sesión - MisuTicket</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#FF6600",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro"]
                    },
                },
            },
        }
    </script>
</head>
<x-guest-layout>
    <div class="bg-white min-h-screen flex items-center justify-center font-display antialiased p-4 relative overflow-hidden">
        
        <main class="relative z-10 w-full max-w-md bg-white">
            <div class="p-8 md:p-10">
                
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-black text-black tracking-tighter uppercase">
                        MISU<span class="text-[#FF6600]">TICKETS</span>
                    </h1>
                    <p class="text-gray-400 text-[10px] mt-2 uppercase tracking-[0.3em] font-bold">Bienvenido de vuelta</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus 
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm transition-all outline-none" 
                            placeholder="juan@ejemplo.com" />
                        @error('email')
                            <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-[#FF6600] hover:text-black uppercase tracking-tight transition-all">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm outline-none" 
                            placeholder="••••••••" />
                        @error('password')
                            <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center ml-1">
                        <input id="remember_me" name="remember" type="checkbox" class="rounded border-gray-300 text-[#FF6600] focus:ring-[#FF6600] h-4 w-4 outline-none"/>
                        <label for="remember_me" class="ml-2 text-[10px] font-black text-gray-800 uppercase tracking-widest">Mantener sesión iniciada</label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#FF6600] hover:bg-[#ff7a21] text-white text-xs font-black py-5 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2">
                            Iniciar Sesión
                            <span class="material-icons text-lg">login</span>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-[10px] text-black uppercase tracking-widest font-bold">
                        ¿No tienes cuenta? 
                        <a href="{{ route('register') }}" class="text-[#FF6600] ml-1 uppercase hover:underline">Regístrate</a>
                    </p>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>