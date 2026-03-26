@extends('layouts.admin')

@section('content')
<div class="font-display max-w-5xl mx-auto" x-data="editEventForm({{ $event->venue->zones->toJson() }}, {{ $event->eventZones->toJson() }})">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">edit</span>
                Editar Evento
            </h1>
        </div>
        <a href="{{ route('admin.events.index') }}" class="text-slate-500 hover:text-slate-900 text-[10px] font-black uppercase tracking-widest flex items-center gap-1 transition-colors">
            <span class="material-icons text-sm">arrow_back</span> Volver
        </a>
    </div>

    {{-- Alertas de Error --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-600 text-xs font-bold">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8 md:p-10">
        <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Título --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Título</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                </div>
                
                {{-- Categoría --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Categoría</label>
                    <select name="category_id" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm outline-none cursor-pointer">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $event->category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lugar (Venue) --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Lugar (No editable)</label>
                    <div class="w-full px-5 py-4 bg-slate-100 border border-slate-200 rounded-2xl text-slate-500 text-sm font-bold flex items-center gap-2">
                        <span class="material-icons text-xs">place</span> {{ $event->venue->name }}
                        <input type="hidden" name="venue_id" value="{{ $event->venue_id }}">
                    </div>
                </div>
            </div>

            {{-- PANEL DINÁMICO DE ZONAS --}}
            <div class="bg-slate-50/50 border border-slate-100 rounded-[2.5rem] p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100">
                        <span class="material-icons text-[#FF6600] text-lg">payments</span>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Precios y Disponibilidad</h3>
                </div>

                <div class="space-y-3">
                    <template x-for="(zone, index) in venueZones" :key="zone.id">
                        <div x-data="{ 
                                active: isZoneActive(zone.id),
                                price: getZoneData(zone.id, 'price'),
                                capacity: getZoneData(zone.id, 'capacity')
                             }" 
                             class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center bg-white p-4 rounded-2xl border border-slate-100 transition-all"
                             :class="!active ? 'bg-slate-50/50 border-dashed opacity-60' : ''">
                            
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" :name="'zones['+index+'][is_active]'" value="1" x-model="active" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#FF6600] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                                <span class="text-xs font-black text-slate-700 uppercase" x-text="zone.name"></span>
                                <input type="hidden" :name="'zones['+index+'][venue_zone_id]'" :value="zone.id">
                            </div>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400 uppercase">Stock</span>
                                <input type="number" :name="'zones['+index+'][capacity]'" x-model="capacity" :required="active" :disabled="!active"
                                    class="w-full pl-14 pr-4 py-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#FF6600]/20 disabled:bg-slate-100">
                            </div>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-[#FF6600]">$</span>
                                <input type="number" step="0.01" :name="'zones['+index+'][price]'" x-model="price" :required="active" :disabled="!active"
                                    class="w-full pl-8 pr-4 py-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#FF6600]/20 disabled:bg-slate-100">
                            </div>

                            <div class="hidden md:block text-right">
                                <span class="text-[9px] font-black uppercase tracking-widest" :class="active ? 'text-[#FF6600]' : 'text-slate-300'" x-text="active ? 'Incluida' : 'Omitida'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- GESTIÓN DE MEDIOS --}}
            <div class="bg-slate-50/50 border border-slate-100 rounded-[2.5rem] p-6 md:p-8 space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100">
                        <span class="material-icons text-[#FF6600] text-lg">image</span>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Multimedia del Evento</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Imagen Principal --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1 italic">Imagen Principal (Card)</label>
                        <div class="relative w-full h-40 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                            <img src="{{ $event->image_path ?? 'https://placehold.co/400x400?text=Sin+Imagen' }}" class="w-full h-full object-cover">
                        </div>
                        <input type="file" name="image" accept="image/*" class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer">
                    </div>

                    {{-- Banner (Hero) --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-[#FF6600] uppercase tracking-widest ml-1">Banner Superior (Hero)</label>
                        <div class="relative w-full h-40 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                            {{-- Corregido: hero_path --}}
                            <img src="{{ $event->hero_path ?? 'https://placehold.co/800x400/1a1a1a/FFFFFF?text=Sin+Banner' }}" class="w-full h-full object-cover">
                        </div>
                        <input type="file" name="hero_image" accept="image/*" class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#FF6600]/10 file:text-[#FF6600] hover:file:bg-[#FF6600]/20 cursor-pointer">
                    </div>

                    {{-- Flyer (Vertical) --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-[#FF6600] uppercase tracking-widest ml-1">Flyer Lateral (Vertical)</label>
                        <div class="relative w-full h-40 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                            {{-- Corregido: flyer_path --}}
                            <img src="{{ $event->flyer_path ?? 'https://placehold.co/400x600/1a1a1a/FFFFFF?text=Sin+Flyer' }}" class="w-full h-full object-cover">
                        </div>
                        <input type="file" name="flyer_image" accept="image/*" class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#FF6600]/10 file:text-[#FF6600] hover:file:bg-[#FF6600]/20 cursor-pointer">
                    </div>
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Descripción</label>
                <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 outline-none resize-none text-sm">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Fecha y Hora</label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 text-sm outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Estado</label>
                    <select name="status" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 text-sm outline-none cursor-pointer">
                        <option value="Draft" {{ old('status', $event->status) == 'Draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="Published" {{ old('status', $event->status) == 'Published' ? 'selected' : '' }}>Publicado</option>
                        <option value="Cancelled" {{ old('status', $event->status) == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center ml-1 bg-slate-50 p-4 rounded-2xl border border-slate-100 w-max">
                <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }} class="h-4 w-4 text-[#FF6600] border-gray-300 rounded focus:ring-[#FF6600]">
                <label for="is_featured" class="ml-3 block text-[10px] font-black text-slate-800 uppercase tracking-widest cursor-pointer">Marcar como Evento Destacado</label>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-[#FF6600] text-white text-xs font-black py-4 px-12 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2">
                    Actualizar Evento <span class="material-icons text-sm">save</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function editEventForm(venueZones, savedZones) {
        return {
            venueZones: venueZones,
            savedZones: savedZones,
            isZoneActive(venueZoneId) {
                return this.savedZones.some(z => z.venue_zone_id == venueZoneId);
            },
            getZoneData(venueZoneId, field) {
                const found = this.savedZones.find(z => z.venue_zone_id == venueZoneId);
                return found ? found[field] : '';
            }
        }
    }
</script>
@endsection