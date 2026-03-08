@extends('layouts.admin')

@section('content')
<div class="font-display max-w-4xl mx-auto">
    {{-- Cabecera --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#FF6600] hover:shadow-lg flex items-center justify-center transition-all">
            <span class="material-icons">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">category</span>
                Nueva Categoría
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Define un nuevo segmento para tus eventos</p>
        </div>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        @csrf
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Nombre de la Categoría --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Nombre de Categoría</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Conciertos" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Icono (Selector Visual de Cuadrícula) --}}
                <div class="md:col-span-2" x-data="{ 
                    selectedIcon: '{{ old('icon', 'label') }}',
                    icons: [
                        { name: 'music_note', label: 'Música' },
                        { name: 'stadium', label: 'Deportes' },
                        { name: 'theater_comedy', label: 'Teatro' },
                        { name: 'festival', label: 'Festivales' },
                        { name: 'star', label: 'VIP' },
                        { name: 'restaurant', label: 'Comida' },
                        { name: 'palette', label: 'Arte' },
                        { name: 'nightlife', label: 'Fiesta' },
                        { name: 'movie', label: 'Cine' },
                        { name: 'local_activity', label: 'Ticket' },
                        { name: 'celebration', label: 'Social' },
                        { name: 'confirmation_number', label: 'Evento' },
                        { name: 'mic', label: 'Podcast' },
                        { name: 'brush', label: 'Taller' },
                        { name: 'fitness_center', label: 'Gym' }
                    ] 
                }">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1">
                        Selecciona un Icono Representativo
                    </label>

                    {{-- Input oculto que enviará el valor al controlador --}}
                    <input type="hidden" name="icon" :value="selectedIcon">

                    {{-- Grid de Selección --}}
                    <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-8 gap-3">
                        <template x-for="icon in icons" :key="icon.name">
                            <button type="button" 
                                @click="selectedIcon = icon.name"
                                :class="selectedIcon === icon.name ? 'border-[#FF6600] bg-[#FF6600]/5 text-[#FF6600] shadow-md' : 'border-slate-100 bg-slate-50 text-slate-400 hover:border-slate-200'"
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all duration-200 group">
                                
                                <span class="material-icons text-2xl mb-1" x-text="icon.name"></span>
                                <span class="text-[8px] font-black uppercase tracking-tighter" x-text="icon.label"></span>
                                
                                {{-- Checkmark cuando está seleccionado --}}
                                <div x-show="selectedIcon === icon.name" class="absolute top-1 right-1">
                                    <span class="material-icons text-[12px]">check_circle</span>
                                </div>
                            </button>
                        </template>
                    </div>

                    @error('icon') <p class="text-rose-500 text-[10px] font-bold mt-4 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

        {{-- Footer del Formulario --}}
        <div class="bg-slate-50/50 p-8 border-t border-slate-100 flex justify-end items-center gap-4">
            <a href="{{ route('admin.categories.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Cancelar</a>
            <button type="submit" class="bg-slate-900 hover:bg-[#FF6600] text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-200">
                Guardar Categoría
            </button>
        </div>
    </form>
</div>
@endsection