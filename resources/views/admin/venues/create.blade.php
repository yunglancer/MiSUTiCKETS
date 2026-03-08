@extends('layouts.admin')

@section('content')
<div class="font-display max-w-4xl mx-auto">
    {{-- Cabecera --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.venues.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#FF6600] hover:shadow-lg flex items-center justify-center transition-all">
            <span class="material-icons">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">add_location</span>
                Nuevo Recinto
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Registrar nueva locación para eventos</p>
        </div>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('admin.venues.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        @csrf
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nombre del Recinto --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Nombre del Lugar</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Teatro Nacional" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Ciudad --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Ej: Maracay" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    @error('city') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Capacidad --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Capacidad Total</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-lg">groups</span>
                        <input type="number" name="capacity" value="{{ old('capacity') }}" placeholder="0" 
                            class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    </div>
                    @error('capacity') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Dirección Exacta</label>
                    <textarea name="address" rows="3" placeholder="Ej: Av. Bolívar, 1234 Maracay" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>{{ old('address') }}</textarea>
                    @error('address') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Footer del Formulario --}}
        <div class="bg-slate-50/50 p-8 border-t border-slate-100 flex justify-end items-center gap-4">
            <a href="{{ route('admin.venues.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Cancelar</a>
            <button type="submit" class="bg-slate-900 hover:bg-[#FF6600] text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-200">
                Guardar Recinto
            </button>
        </div>
    </form>
</div>
@endsection