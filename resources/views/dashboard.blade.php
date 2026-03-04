@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">
            <span class="material-icons text-[#FF6600]">speed</span>
            Panel de <span class="text-[#FF6600]">Control</span>
        </h1>
        <p class="text-slate-500 text-sm mt-1 font-medium">Gestión integral de la plataforma MiSUTiCKETS</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-lg shadow-slate-200/50 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#FF6600] rounded-full opacity-10 blur-2xl"></div>
        
        <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center text-[#FF6600] shrink-0 z-10">
            <span class="material-icons text-4xl">admin_panel_settings</span>
        </div>
        <div class="z-10">
            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">¡Bienvenido al centro de mando!</h2>
            <p class="mt-2 text-slate-500 text-sm leading-relaxed">
                Has iniciado sesión exitosamente como administrador. Desde aquí tienes el control absoluto para gestionar el catálogo de eventos, administrar los recintos y configurar las categorías. ¡Hagamos que el próximo evento sea un éxito!
            </p>
        </div>
    </div>
</div>
@endsection