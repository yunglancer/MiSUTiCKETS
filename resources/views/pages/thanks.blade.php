@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#1a1a1a] flex flex-col font-display antialiased">
    <meta http-equiv="refresh" content="5;url={{ url('/') }}">

    <div class="flex-grow flex items-center justify-center px-4">
        <div class="text-center">
            <div class="w-20 h-20 bg-[#FF6600] rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-[#FF6600]/20">
                <span class="material-icons text-white text-4xl">check</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-4 uppercase tracking-tighter">
                ¡Gracias por <span class="text-[#FF6600]">tu mensaje!</span>
            </h1>
            <p class="text-slate-400 text-lg mb-8 max-w-md mx-auto">
                Hemos recibido tu información correctamente. Serás redirigido a la vitrina principal en unos segundos...
            </p>
        </div>
    </div>

    <div class="light bg-white border-t border-slate-200">
        <x-footer />
    </div>
</div>
@endsection