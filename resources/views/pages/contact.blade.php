@extends('layouts.app')

@section('content')
<div class="antialiased font-display text-slate-900">
    
    <main class="min-h-screen bg-[#1a1a1a] flex flex-col">
        
        <div class="flex-grow py-20 px-4">
            <div class="container mx-auto max-w-6xl">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    
                    <div class="space-y-8">
                        <div>
                            <span class="inline-block px-3 py-1 bg-[#FF6600] text-white text-[10px] font-black rounded mb-4 uppercase tracking-[0.2em] shadow-md">
                                SOPORTE MiSUTiCKETS
                            </span>
                            <h2 class="text-5xl lg:text-7xl font-black text-white leading-[0.9] uppercase tracking-tighter">
                                ¿Tienes dudas? <br>
                                <span class="text-[#FF6600]">Escríbenos</span>
                            </h2>
                        </div>

                        <p class="text-lg text-slate-300 font-medium leading-relaxed max-w-md">
                            Nuestro equipo de atención al cliente está disponible para resolver tus dudas sobre compras, eventos y acceso.
                        </p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-5 bg-white/5 border border-white/10 rounded-2xl w-fit">
                                <div class="w-12 h-12 bg-[#FF6600] rounded-xl flex items-center justify-center text-white shadow-lg shadow-[#FF6600]/20">
                                    <span class="material-icons">mail</span>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">Email de Contacto</p>
                                    <p class="text-white font-bold">soporte@misutickets.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#111111] border border-white/10 p-8 md:p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#FF6600]/10 blur-[80px] rounded-full"></div>
                        
                        <form action="{{ route('pages.contact.send') }}" method="POST" class="relative z-10 space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-widest font-black text-[#FF6600]">Nombre Completo</label>
                                    <input type="text" name="name" placeholder="Ej. Juan Pérez" required 
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#FF6600]/50 focus:border-[#FF6600] transition-all font-medium">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] uppercase tracking-widest font-black text-[#FF6600]">Correo</label>
                                    <input type="email" name="email" placeholder="tu@correo.com" required 
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#FF6600]/50 focus:border-[#FF6600] transition-all font-medium">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-widest font-black text-[#FF6600]">Asunto</label>
                                <div class="relative">
                                    <select name="subject" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:outline-none focus:border-[#FF6600] transition-all appearance-none font-medium">
                                        <option value="soporte" class="bg-[#111]">Problema con mi ticket</option>
                                        <option value="pago" class="bg-[#111]">Duda sobre pagos</option>
                                        <option value="otro" class="bg-[#111]">Otro motivo</option>
                                    </select>
                                    <span class="material-icons absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-widest font-black text-[#FF6600]">Mensaje</label>
                                <textarea name="message" rows="4" placeholder="Escribe aquí tu mensaje..." required 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white placeholder:text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#FF6600]/50 focus:border-[#FF6600] transition-all resize-none font-medium"></textarea>
                            </div>

                            <button type="submit" class="group w-full py-5 bg-[#FF6600] text-white font-black rounded-2xl hover:bg-white hover:text-slate-900 transition-all flex items-center justify-center gap-3 shadow-xl shadow-[#FF6600]/20 uppercase tracking-widest text-sm active:scale-[0.98]">
                                Enviar Mensaje
                                <span class="material-icons transition-transform group-hover:translate-x-1">arrow_forward</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white">
            <div class="light">
                <x-footer />
            </div>
        </div>
    </main>
    
</div>

<style>
    /* Aseguramos que use la fuente del Welcome */
    .font-display { font-family: 'Be Vietnam Pro', sans-serif !important; }
</style>
@endsection