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

    {{-- BLOQUE DE ERRORES --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-600 shadow-sm animate-shake">
            <span class="material-icons">error_outline</span>
            <p class="text-xs font-black uppercase tracking-tight">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-600 shadow-sm">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li class="text-[10px] font-black uppercase tracking-widest">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                </div>

                {{-- Ciudad --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Ej: Maracay" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                </div>

                {{-- Capacidad Total --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Capacidad Total del Recinto</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-lg">groups</span>
                        <input type="number" name="capacity" value="{{ old('capacity', 0) }}" placeholder="0" 
                            class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>
                    </div>
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2 border-b border-slate-100 pb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Dirección Exacta</label>
                    <textarea name="address" rows="3" placeholder="Ej: Av. Bolívar, Maracay" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600] transition-all" required>{{ old('address') }}</textarea>
                </div>

                {{-- Configuración de Zonas Dinámicas --}}
                <div class="md:col-span-2 pt-4" x-data="{ 
                    zones: {{ collect(old('zones', [['name' => 'General', 'capacity' => 0]]))->toJson() }}, 
                    addZone() { this.zones.push({ name: '', capacity: 0 }) }, 
                    removeZone(index) { this.zones.splice(index, 1) } 
                }">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Zonas y Aforos</h3>
                            <p class="text-slate-400 text-[9px] uppercase tracking-widest font-bold mt-0.5">La suma de capacidades debe ser igual al total del recinto</p>
                        </div>
                        <button type="button" @click="addZone()" class="bg-slate-50 hover:bg-slate-100 text-slate-600 text-[10px] font-black py-2.5 px-4 rounded-xl uppercase tracking-widest transition-all flex items-center gap-2 border border-slate-100">
                            <span class="material-icons text-sm">add</span> Añadir Zona
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(zone, index) in zones" :key="index">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end animate-fade-in bg-slate-50/50 p-4 rounded-2xl border border-dashed border-slate-200">
                                <div class="sm:col-span-7">
                                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nombre de Zona</label>
                                    <input type="text" :name="'zones['+index+'][name]'" x-model="zone.name" placeholder="Ej: VIP, Platea..." 
                                        class="w-full bg-white border-none rounded-xl px-4 py-3.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600]" required>
                                </div>

                                <div class="sm:col-span-4">
                                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Capacidad</label>
                                    <input type="number" :name="'zones['+index+'][capacity]'" x-model.number="zone.capacity" placeholder="0" 
                                        class="w-full bg-white border-none rounded-xl px-4 py-3.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#FF6600]" required>
                                </div>

                                <div class="sm:col-span-1">
                                    <button type="button" @click="removeZone(index)" x-show="zones.length > 1"
                                        class="w-full h-[46px] rounded-xl bg-white text-slate-400 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all border border-slate-100">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-slate-50/50 p-8 border-t border-slate-100 flex justify-end items-center gap-4">
            <a href="{{ route('admin.venues.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Cancelar</a>
            <button type="submit" class="bg-slate-900 hover:bg-[#FF6600] text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-200">
                Guardar Recinto
            </button>
        </div>
    </form>
</div>
@endsection