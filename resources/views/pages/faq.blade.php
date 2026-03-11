@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#1a1a1a] font-display antialiased pb-20">
    <div class="py-16 px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter">
            Preguntas <span class="text-[#FF6600]">Frecuentes</span>
        </h1>
        <p class="text-slate-400 mt-4 max-w-2xl mx-auto">
            Todo lo que necesitas saber sobre nuestros servicios y procesos. Si no encuentras tu duda aquí, contáctanos.
        </p>
    </div>

    <div class="max-w-3xl mx-auto px-4">
        <div class="space-y-4">
            
            <div class="bg-[#242424] border border-slate-800 rounded-xl overflow-hidden transition-all duration-300">
                <button class="faq-button w-full flex items-center justify-between p-6 text-left text-white hover:bg-[#2a2a2a]">
                    <span class="font-bold text-lg">¿Cómo realizo un pedido?</span>
                    <span class="material-icons text-[#FF6600] transform transition-transform duration-300">expand_more</span>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-slate-400 border-t border-slate-800/50">
                    Puedes navegar por nuestra pagina de inicio, seleccionar el evento de tu preferencia y seguir los pasos del carrito de compras. También puedes contactarnos vía WhatsApp para atención personalizada.
                </div>
            </div>

            <div class="bg-[#242424] border border-slate-800 rounded-xl overflow-hidden transition-all duration-300">
                <button class="faq-button w-full flex items-center justify-between p-6 text-left text-white hover:bg-[#2a2a2a]">
                    <span class="font-bold text-lg">¿Cuáles son los métodos de pago?</span>
                    <span class="material-icons text-[#FF6600] transform transition-transform duration-300">expand_more</span>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-slate-400 border-t border-slate-800/50">
                    Aceptamos transferencias bancarias, Pago Móvil, Zelle y efectivo en nuestras sedes físicas.
                </div>
            </div>

            <div class="bg-[#242424] border border-slate-800 rounded-xl overflow-hidden transition-all duration-300">
                <button class="faq-button w-full flex items-center justify-between p-6 text-left text-white hover:bg-[#2a2a2a]">
                    <span class="font-bold text-lg">¿Cuanto tiempo demora en llegar por correo mi entrada?</span>
                    <span class="material-icons text-[#FF6600] transform transition-transform duration-300">expand_more</span>
                </button>
                <div class="faq-answer hidden p-6 pt-0 text-slate-400 border-t border-slate-800/50">
                    La entrada puede demorar entre 5 a 10 minutos en llegar por correo, luego de realizar la compra.
                </div>
            </div>

        </div>

        <div class="mt-12 text-center bg-[#FF6600]/10 p-8 rounded-2xl border border-[#FF6600]/20">
            <p class="text-white font-bold mb-4">¿Aún tienes dudas?</p>
            <a href="{{ route('pages.contact') }}" class="inline-block bg-[#FF6600] text-white font-black px-8 py-3 rounded-full uppercase tracking-tighter hover:bg-[#e65c00] transition-all">
                Escríbenos directamente
            </a>
        </div>
    </div>
</div>

<div class="light bg-white border-t border-slate-200">
    <x-footer />
</div>

<script>
    document.querySelectorAll('.faq-button').forEach(button => {
        button.addEventListener('click', () => {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('.material-icons');
            
            // Alternar visibilidad
            answer.classList.toggle('hidden');
            
            // Rotar icono
            icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
</script>
@endsection