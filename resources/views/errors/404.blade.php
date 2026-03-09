<x-guest-layout>
    <div class="bg-white min-h-screen flex items-center justify-center font-display antialiased p-6 relative overflow-hidden">
        
        <div class="absolute top-[-10%] left-[-10%] w-72 h-72 bg-[#FF6600]/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-[#FF6600]/10 rounded-full blur-3xl"></div>

        <main class="relative z-10 w-full max-w-lg text-center">
            
            <div class="mb-8 inline-flex items-center justify-center w-28 h-28 bg-orange-50 rounded-full text-[#FF6600] animate-bounce">
                <span class="material-icons text-7xl">confirmation_number</span>
            </div>

            <h2 class="text-[#FF6600] font-black text-9xl tracking-tighter opacity-10 select-none">404</h2>

            <div class="relative -mt-16 mb-12">
                <h1 class="text-4xl md:text-5xl font-black text-black uppercase tracking-tight leading-none">
                    ¡Ups! Parece que <br>
                    <span class="text-[#FF6600]">esta entrada no existe</span>
                </h1>
                <p class="mt-6 text-gray-500 text-sm font-medium max-w-xs mx-auto leading-relaxed uppercase tracking-widest">
                    Buscamos en toda la ticketera pero no pudimos encontrar lo que buscabas.
                </p>
            </div>

            <div class="flex flex-col items-center justify-center">
                <a href="/" class="w-full sm:w-auto bg-black hover:bg-gray-800 text-white text-[11px] font-black px-12 py-5 rounded-2xl uppercase tracking-[0.25em] transition-all flex items-center justify-center gap-3 shadow-2xl active:scale-95">
                    <span class="material-icons text-base">shopping_bag</span>
                    Volver a la tienda
                </a>
            </div>

            <div class="mt-16 opacity-40">
                <p class="text-[10px] font-black text-black uppercase tracking-[0.4em]">
                    MISU<span class="text-[#FF6600]">TICKETS</span>
                </p>
            </div>
        </main>
    </div>
</x-guest-layout>