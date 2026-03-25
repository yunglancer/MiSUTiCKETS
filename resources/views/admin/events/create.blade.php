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

    {{-- BLOQUE DE ERRORES DE VALIDACIÓN --}}
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

    {{-- BLOQUE DE ERROR DE AFORO (SESIÓN) --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-600 border border-rose-700 rounded-2xl flex items-center gap-3 text-white shadow-lg animate-shake">
            <span class="material-icons">warning</span>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest leading-none mb-1">Error de Aforo Detectado</p>
                <p class="text-xs font-bold opacity-90">{{ session('error') }}</p>
            </div>
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
                            <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }} data-capacity="{{ $venue->capacity }}">
                                {{ $venue->name }} (Capacidad: {{ $venue->capacity }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PANEL DINÁMICO DE ZONAS Y PRECIOS --}}
            <div x-show="zones.length > 0" x-transition.opacity class="bg-slate-50/50 border border-slate-100 rounded-[2.5rem] p-6 md:p-8 animate-fade-in">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100">
                            <span class="material-icons text-[#FF6600] text-lg">payments</span>
                        </div>
                        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Configuración de Zonas y Precios</h3>
                    </div>

                    {{-- Contador de Aforo en Tiempo Real --}}
                    <div class="px-4 py-2 rounded-xl border flex flex-col items-end" :class="isOverCapacity ? 'bg-rose-50 border-rose-200' : 'bg-emerald-50 border-emerald-200'">
                        <span class="text-[8px] font-black uppercase tracking-widest" :class="isOverCapacity ? 'text-rose-600' : 'text-emerald-600'">Aforo Total Asignado</span>
                        <span class="text-sm font-black" :class="isOverCapacity ? 'text-rose-700' : 'text-emerald-700'">
                            <span x-text="currentTotalCapacity"></span> / <span x-text="venueMaxCapacity"></span>
                        </span>
                    </div>
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
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-700 uppercase tracking-tight" x-text="zone.name"></span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Máx Físico: <span x-text="zone.capacity"></span></span>
                                </div>
                                <input type="hidden" :name="'zones['+index+'][venue_zone_id]'" :value="zone.id">
                            </div>

                            {{-- Capacidad (Stock) --}}
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Stock</span>
                                <input type="number" 
                                    :name="'zones['+index+'][capacity]'" 
                                    placeholder="0" 
                                    x-model="zone.inputCapacity"
                                    @input="calculateTotal()"
                                    :required="active" 
                                    :disabled="!active"
                                    class="w-full pl-14 pr-4 py-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#FF6600]/20 transition-all disabled:bg-slate-100"
                                    :class="zone.inputCapacity > zone.capacity ? 'ring-2 ring-rose-500 bg-rose-50' : ''">
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
                                      :class="active ? (zone.inputCapacity > zone.capacity ? 'text-rose-600' : 'text-[#FF6600]') : 'text-slate-300'"
                                      x-text="active ? (zone.inputCapacity > zone.capacity ? 'Excede zona' : 'Zona activa') : 'Excluida'">
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

            {{-- SECCIÓN DE IMÁGENES --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-slate-100 pt-6">
                {{-- Imagen de Portada --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Portada (Mini)</label>
                    <div id="preview-main" class="hidden mb-2">
                        <div class="relative w-full h-32 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden">
                            <img id="img-main" src="#" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <input type="file" name="image" onchange="preview(this, 'main')" accept="image/*"
                           class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                </div>

                {{-- Hero Image --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Banner Hero (Ancho)</label>
                    <div id="preview-hero" class="hidden mb-2">
                        <div class="relative w-full h-32 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden">
                            <img id="img-hero" src="#" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <input type="file" name="hero_image" onchange="preview(this, 'hero')" accept="image/*"
                           class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                </div>

                {{-- Flyer Image --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest ml-1">Flyer (Vertical)</label>
                    <div id="preview-flyer" class="hidden mb-2">
                        <div class="relative w-full h-32 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden">
                            <img id="img-flyer" src="#" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <input type="file" name="flyer_image" onchange="preview(this, 'flyer')" accept="image/*"
                           class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                </div>
            </div>

            <div class="flex items-center ml-1 bg-slate-50 p-4 rounded-2xl border border-slate-100 w-max">
                <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }} value="1" class="h-4 w-4 text-[#FF6600] border-gray-300 rounded focus:ring-[#FF6600]">
                <label for="is_featured" class="ml-3 block text-[10px] font-black text-slate-800 uppercase tracking-widest cursor-pointer">Marcar como Evento Destacado</label>
            </div>

            <div class="flex flex-col items-end gap-3 pt-4">
                <p x-show="isOverCapacity" x-transition class="text-rose-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-1">
                    <span class="material-icons text-sm">error</span> Reduce el stock para poder guardar
                </p>
                <button type="submit" 
                        :disabled="isOverCapacity"
                        class="w-full md:w-auto bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale">
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
            venueMaxCapacity: 0,
            currentTotalCapacity: 0,
            isOverCapacity: false,

            init() {
                const selectVenue = document.querySelector('select[name="venue_id"]');
                if (selectVenue.value) {
                    this.fetchZones(selectVenue.value);
                }
            },

            async fetchZones(venueId) {
                if (!venueId) {
                    this.zones = [];
                    this.venueMaxCapacity = 0;
                    return;
                }

                // Obtener capacidad máxima del recinto desde el atributo data
                const select = document.querySelector('select[name="venue_id"]');
                const selectedOption = select.options[select.selectedIndex];
                this.venueMaxCapacity = parseInt(selectedOption.getAttribute('data-capacity')) || 0;

                try {
                    const response = await fetch(`/admin/venues/${venueId}/zones-list`);
                    const data = await response.json();
                    // Añadimos una propiedad para rastrear el input de capacidad
                    this.zones = data.map(zone => ({
                        ...zone,
                        inputCapacity: 0
                    }));
                    this.calculateTotal();
                } catch (error) {
                    console.error('Error al cargar zonas:', error);
                }
            },

            calculateTotal() {
                this.currentTotalCapacity = this.zones.reduce((sum, zone) => {
                    return sum + (parseInt(zone.inputCapacity) || 0);
                }, 0);

                // Verificar si excede el total global o si alguna zona individual excede su capacidad física
                const anyZoneExceeded = this.zones.some(zone => (parseInt(zone.inputCapacity) || 0) > zone.capacity);
                this.isOverCapacity = (this.currentTotalCapacity > this.venueMaxCapacity) || anyZoneExceeded;
            }
        }
    }

    function preview(input, type) {
        const container = document.getElementById(`preview-${type}`);
        const image = document.getElementById(`img-${type}`);
        const [file] = input.files;
        
        if (file) {
            container.classList.remove('hidden');
            image.src = URL.createObjectURL(file);
        }
    }
</script>
@endsection