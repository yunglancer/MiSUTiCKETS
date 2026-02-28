<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registro - MisuTicket</title>
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
                    <p class="text-gray-400 text-[10px] mt-2 uppercase tracking-[0.3em] font-bold">Crea tu cuenta</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Nombre Completo</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus 
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm transition-all outline-none" 
                            placeholder="Ej. Juan Pérez" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm transition-all outline-none" 
                            placeholder="juan@ejemplo.com" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Cédula</label>
                            <input id="document_id" name="document_id" type="text" required 
                                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm outline-none" 
                                placeholder="V-1234567" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Teléfono</label>
                            <input id="phone" name="phone" type="text" required 
                                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm outline-none" 
                                placeholder="0412 1234567" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Contraseña</label>
                            <input id="password" name="password" type="password" required 
                                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm outline-none" 
                                placeholder="••••••••" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Confirmar</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm outline-none" 
                                placeholder="••••••••" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#FF6600] hover:bg-[#ff7a21] text-white text-xs font-black py-5 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30">
                            Registrarse
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-[10px] text-black uppercase tracking-widest font-bold">
                        ¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}" class="text-[#FF6600] ml-1 uppercase hover:underline">Inicia Sesión</a>
                    </p>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>