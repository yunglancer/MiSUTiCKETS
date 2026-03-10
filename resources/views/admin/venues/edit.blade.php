@extends('layouts.admin')

@section('content')
<div class="font-display max-w-4xl mx-auto">
    {{-- Cabecera --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.venues.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-[#FF6600] hover:shadow-lg flex items-center justify-center transition-all">
            <span class="material-icons">arrow_back</span>
        </a>
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Editar Recinto</h1>
                <span class="bg-slate-100 text-slate-500 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest">ID: #{{ $venue->id }}</span>
            </div>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Actualiza la información de la locación</p>
        </div>
    </div>

    {{-- Formulario de Edición --}}
    <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST" class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nombre del Recinto --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Nombre del Lugar</label>
                    <input type="text" name="name" value="{{ old('name', $venue->name) }}" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Ciudad --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city', $venue->city) }}" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    @error('city') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Capacidad --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Capacidad Total</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-lg">groups</span>
                        <input type="number" name="capacity" value="{{ old('capacity', $venue->capacity) }}" 
                            class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    </div>
                    @error('capacity') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2 border-b border-slate-100 pb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Dirección Exacta</label>
                    <textarea name="address" rows="3" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>{{ old('address', $venue->address) }}</textarea>
                    @error('address') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                {{-- Zonas del Recinto --}}
                <div class="md:col-span-2 pt-4" x-data="{ 
                    zones: {{ $venue->zones->count() > 0 ? $venue->zones->pluck('name') : "['General']" }}, 
                    addZone() { this.zones.push('') }, 
                    removeZone(index) { this.zones.splice(index, 1) } 
                }">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Zonas del Recinto</h3>
                            <p class="text-slate-400 text-[9px] uppercase tracking-widest font-bold mt-0.5">Define las áreas disponibles para este lugar</p>
                        </div>
                        <button type="button" @click="addZone()" class="bg-slate-50 hover:bg-slate-100 text-slate-600 text-[10px] font-black py-2.5 px-4 rounded-xl uppercase tracking-widest transition-all flex items-center gap-2 border border-slate-100">
                            <span class="material-icons text-sm">add</span> Añadir Zona
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template x-for="(zone, index) in zones" :key="index">
                            <div class="relative group animate-fade-in">
                                <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1" x-text="'Nombre de Zona ' + (index + 1)"></label>
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-base">label</span>
                                        <input type="text" name="zones[]" x-model="zones[index]" placeholder="Ej: VIP, Platea Alta..." 
                                            class="w-full bg-slate-50 border-none rounded-2xl pl-11 pr-4 py-3.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                                    </div>
                                    <button type="button" @click="removeZone(index)" x-show="zones.length > 1"
                                        class="w-11 h-11 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all border border-slate-100 shadow-sm">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer del Formulario --}}
        <div class="bg-slate-50/50 p-8 border-t border-slate-100 flex justify-end items-center gap-4">
            <a href="{{ route('admin.venues.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#FF6600] hover:bg-slate-900 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/20">
                Actualizar Recinto
            </button>
        </div>
    </form>
</div>
@endsection