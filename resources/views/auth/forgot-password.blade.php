<x-guest-layout>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center font-display antialiased p-4 relative overflow-hidden">
        
        <main class="relative z-10 w-full max-w-md bg-white rounded-[2rem] shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-10">
                
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-black text-black tracking-tighter uppercase">
                        MISU<span class="text-[#FF6600]">TICKETS</span>
                    </h1>
                    <p class="text-gray-400 text-[10px] mt-3 uppercase tracking-[0.2em] font-bold">Recuperar Acceso</p>
                </div>

                <div class="mb-6">
                    <p class="text-gray-500 text-xs text-center font-medium leading-relaxed">
                        ¿Olvidaste tu contraseña? No hay problema. Dinos tu correo y te enviaremos un enlace para que elijas una nueva.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 rounded-2xl border border-green-100">
                        <p class="text-green-600 text-[10px] font-black uppercase tracking-widest text-center">
                            {{ session('status') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus 
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-black text-sm transition-all outline-none" 
                            placeholder="tu@correo.com" />
                        
                        @error('email')
                            <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#FF6600] hover:bg-[#ff7a21] text-white text-xs font-black py-5 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2">
                            Enviar enlace
                            <span class="material-icons text-lg">send</span>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center pt-6 border-t border-gray-50">
                    <a href="{{ route('login') }}" class="text-[10px] text-gray-400 uppercase tracking-widest font-bold hover:text-[#FF6600] transition-colors flex items-center justify-center gap-1">
                        <span class="material-icons text-sm">arrow_back</span>
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>