@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#1a1a1a] font-display antialiased pb-20">
    
    <div class="pt-20 pb-10 px-4 bg-[#1e1e1e] text-center border-b border-slate-800">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-6xl md:text-8xl font-black text-white uppercase tracking-tighter leading-none mb-4">
                Términos y <span class="text-[#FF6600]">Condiciones</span>
            </h1>
            <p class="text-slate-500 text-lg font-bold uppercase tracking-widest">
                Última actualización: {{ date('d/m/Y') }}
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="text-slate-300 space-y-12">
            
            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">01.</span> Aceptación de Términos
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    Al acceder y utilizar este sitio web, usted acepta cumplir y estar sujeto a los siguientes términos y condiciones de uso. Si no está de acuerdo con alguna parte de estos términos, le rogamos que no utilice nuestros servicios.
                </p>
            </section>

            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">02.</span> Propiedad Intelectual
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    Todo el contenido presente en esta vitrina, incluyendo diseños, logotipos, imágenes y textos, es propiedad exclusiva de la marca y está protegido por las leyes de propiedad intelectual vigentes.
                </p>
            </section>

            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">03.</span> Proceso de Compra
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    Los precios y la disponibilidad de los productos están sujetos a cambios sin previo aviso. Nos reservamos el derecho de cancelar cualquier pedido en caso de errores en el inventario o fallas en el sistema.
                </p>
            </section>

            <section>
                <h2 class="text-3xl font-black text-white flex items-center gap-4 uppercase mb-4">
                    <span class="text-[#FF6600]">04.</span> Responsabilidad
                </h2>
                <p class="text-xl leading-relaxed text-slate-400">
                    No nos hacemos responsables por daños directos o indirectos derivados del uso de este sitio web o de la imposibilidad de acceder al mismo.
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