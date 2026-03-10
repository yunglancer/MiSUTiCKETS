@extends('layouts.admin')

@section('content')
<div class="font-display max-w-5xl mx-auto" x-data="eventForm()">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">add_circle</span>
                Crear Evento
            </h1>
        </div>
        <a href="{{ route('admin.events.index') }}" class="text-slate-500 hover:text-slate-900 text-[10px] font-black uppercase tracking-widest flex items-center gap-1 transition-colors">
            <span class="material-icons text-sm">arrow_back</span> Volver
        </a>
    </div>

    {{-- BLOQUE DE ERRORES: Vital para saber por qué no se guarda --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
            <div class="flex items-center gap-2 text-rose-800 mb-2">
                <span class="material-icons text-sm">error_outline</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Errores de validación</span>
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8 md:p-10">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Título --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Título del Evento</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none" 
                        placeholder="Ej: Concierto de Rock">
                </div>
                
                {{-- Categoría --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Categoría</label>
                    <select name="category_id" required 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none cursor-pointer">
                        <option value="" disabled selected>Seleccione una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lugar (Venue) --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Lugar (Venue)</label>
                    <select name="venue_id" required @change="fetchZones($el.value)"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none cursor-pointer">
                        <option value="" disabled {{ old('venue_id') ? '' : 'selected' }}>Seleccione un lugar</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} ({{ $venue->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PANEL DINÁMICO DE ZONAS Y PRECIOS --}}
            <div x-show="zones.length > 0" x-transition.opacity class="bg-slate-50/50 border border-slate-100 rounded-[2.5rem] p-6 md:p-8 animate-fade-in">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100">
                        <span class="material-icons text-[#FF6600] text-lg">payments</span>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Configuración de Zonas y Precios</h3>
                </div>

                <div class="space-y-3">
                    <template x-for="(zone, index) in zones" :key="zone.id">
                        <div x-data="{ active: true }" 
                             class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center bg-white p-4 rounded-2xl border border-slate-100 transition-all hover:shadow-md"
                             :class="!active ? 'bg-slate-50/50 border-dashed opacity-60' : ''">
                            
                            {{-- Switch Activa/Inactiva --}}
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           :name="'zones['+index+'][is_active]'" 
                                           value="1" 
                                           x-model="active" 
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF6600]"></div>
                                </label>
                                <span class="text-xs font-black text-slate-700 uppercase tracking-tight" x-text="zone.name"></span>
                                <input type="hidden" :name="'zones['+index+'][venue_zone_id]'" :value="zone.id">
                            </div>

                            {{-- Capacidad --}}
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Stock</span>
                                <input type="number" 
                                    :name="'zones['+index+'][capacity]'" 
                                    placeholder="0" 
                                    :required="active" 
                                    :disabled="!active"
                                    class="w-full pl-14 pr-4 py-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#FF6600]/20 transition-all disabled:bg-slate-100">
                            </div>

                            {{-- Precio --}}
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-[#FF6600]">$</span>
                                <input type="number" 
                                    step="0.01" 
                                    :name="'zones['+index+'][price]'" 
                                    placeholder="0.00" 
                                    :required="active" 
                                    :disabled="!active"
                                    class="w-full pl-8 pr-4 py-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#FF6600]/20 transition-all disabled:bg-slate-100">
                            </div>

                            <div class="hidden md:block text-right">
                                <span class="text-[9px] font-black uppercase tracking-widest transition-colors"
                                      :class="active ? 'text-[#FF6600]' : 'text-slate-300'"
                                      x-text="active ? 'Zona activa' : 'Excluida'">
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Descripción</label>
                <textarea name="description" rows="4" 
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Fecha y Hora</label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Estado</label>
                    <select name="status" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none cursor-pointer">
                        <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Publicado</option>
                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Imagen de Portada</label>
                
                <div id="preview-container" class="hidden mb-4">
                    <div class="relative w-full md:w-72 h-44 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 overflow-hidden group">
                        <img id="image-preview" src="#" alt="Vista previa" class="w-full h-full object-cover">
                        <button type="button" onclick="removeImage()" class="absolute top-3 right-3 bg-white/90 p-2 rounded-xl shadow-sm hover:bg-red-50 hover:text-red-600 text-slate-500 transition-all flex items-center justify-center">
                            <span class="material-icons text-lg">delete_outline</span>
                        </button>
                    </div>
                </div>

                <input type="file" name="image" id="image-input" accept="image/*"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-colors cursor-pointer">
            </div>

            <div class="flex items-center ml-1 bg-slate-50 p-4 rounded-2xl border border-slate-100 w-max">
                <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }} value="1" class="h-4 w-4 text-[#FF6600] border-gray-300 rounded focus:ring-[#FF6600]">
                <label for="is_featured" class="ml-3 block text-[10px] font-black text-slate-800 uppercase tracking-widest cursor-pointer">Marcar como Evento Destacado</label>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full md:w-auto bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2">
                    Guardar Evento <span class="material-icons text-sm">rocket_launch</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function eventForm() {
        return {
            zones: [],
            // Al iniciar, si ya había un venue seleccionado (por error de validación), cargamos sus zonas
            init() {
                const initialVenue = document.querySelector('select[name="venue_id"]').value;
                if (initialVenue) {
                    this.fetchZones(initialVenue);
                }
            },
            async fetchZones(venueId) {
                if (!venueId) {
                    this.zones = [];
                    return;
                }
                try {
                    const response = await fetch(`/admin/venues/${venueId}/zones-list`);
                    const data = await response.json();
                    this.zones = data;
                } catch (error) {
                    console.error('Error al cargar zonas:', error);
                }
            }
        }
    }

    // Lógica de Previsualización de Imagen
    const imageInput = document.getElementById('image-input');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('image-preview');

    imageInput.onchange = evt => {
        const [file] = imageInput.files;
        if (file) {
            previewContainer.classList.remove('hidden');
            previewImage.src = URL.createObjectURL(file);
        }
    }

    function removeImage() {
        imageInput.value = "";
        previewContainer.classList.add('hidden');
        previewImage.src = "#";
    }
</script>
@endsection