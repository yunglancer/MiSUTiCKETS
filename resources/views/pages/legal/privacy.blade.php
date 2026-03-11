@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#1a1a1a] font-display antialiased pb-20">
    
    <div class="pt-20 pb-10 px-4 bg-[#1e1e1e] text-center border-b border-slate-800">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-6xl md:text-8xl font-black text-white uppercase tracking-tighter leading-none mb-4">
                Políticas de <span class="text-[#FF6600]">Privacidad</span>
            </h1>
            <p class="text-slate-500 text-lg font-bold uppercase tracking-widest">
                Tu seguridad es nuestra prioridad
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="text-slate-300 space-y-12">
            
            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">01.</span> Recolección de Datos
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    Recopilamos información personal básica como nombre, correo electrónico y número de teléfono únicamente cuando rellenas nuestro formulario de contacto para brindarte una mejor atención.
                </p>
            </section>

            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">02.</span> Uso de la Información
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    Tus datos se utilizan exclusivamente para procesar tus consultas y pedidos. No compartimos, vendemos ni alquilamos tu información personal a terceros bajo ninguna circunstancia.
                </p>
            </section>

            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">03.</span> Seguridad
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    Implementamos medidas de seguridad técnicas para proteger tus datos. Trabajamos con las mejores prácticas de Laravel para mantener tu informacion a salvo.
                </p>
            </section>

        </div>

        <div class="mt-16 text-center">
            <a href="{{ url('/') }}" class="inline-block border-2 border-[#FF6600] text-[#FF6600] hover:bg-[#FF6600] hover:text-white transition-all font-black px-10 py-4 rounded-full uppercase tracking-tighter">
                Volver al inicio
            </a>
        </div>
    </div>
</div>

<div class="light bg-white border-t border-slate-200">
    <x-footer />
</div>
@endsection