@extends('layouts.admin')

@section('content')
<div class="font-display max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">edit_square</span>
                Editar Evento
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">{{ $event->title }}</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="text-slate-500 hover:text-slate-900 text-[10px] font-black uppercase tracking-widest flex items-center gap-1 transition-colors">
            <span class="material-icons text-sm">arrow_back</span> Volver
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8 md:p-10">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Título</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Categoría</label>
                    <select name="category_id" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none appearance-none">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Lugar</label>
                    <select name="venue_id" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none appearance-none">
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Descripción</label>
                <textarea name="description" rows="4" 
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none resize-none">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Fecha</label>
                    <input type="datetime-local" name="event_date" value="{{ date('Y-m-d\TH:i', strtotime($event->event_date)) }}" 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Estado</label>
                    <select name="status" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none appearance-none">
                        <option value="Draft" {{ $event->status == 'Draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="Published" {{ $event->status == 'Published' ? 'selected' : '' }}>Publicado</option>
                        <option value="Cancelled" {{ $event->status == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  {{-- Imagen que ya existe --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Imagen Actual</label>
                        @if($event->image_path)
                            <div class="relative w-full h-44 bg-slate-50 rounded-3xl border border-slate-200 overflow-hidden">
                                <img src="{{ $event->image_path }}" class="w-full h-full object-cover">
                                <div class="absolute top-3 left-3 bg-slate-900/50 backdrop-blur-sm text-white text-[8px] font-bold px-2 py-1 rounded-lg uppercase tracking-widest">En Servidor</div>
                            </div>
                        @else
                            <div class="w-full h-44 bg-slate-50 rounded-3xl border border-dashed border-slate-200 flex items-center justify-center text-slate-400 text-[10px] font-bold uppercase">Sin imagen previa</div>
                        @endif
                    </div>

                    {{-- Vista previa de la nueva imagen --}}
                    <div id="preview-container" class="hidden">
                        <label class="block text-[10px] font-black text-[#FF6600] uppercase tracking-widest mb-2 ml-1">Nueva Vista Previa</label>
                        <div class="relative w-full h-44 bg-slate-50 rounded-3xl border-2 border-dashed border-[#FF6600]/30 overflow-hidden">
                            <img id="image-preview" src="#" alt="Vista previa" class="w-full h-full object-cover">
                            <button type="button" onclick="removeImage()" class="absolute top-3 right-3 bg-white/90 p-2 rounded-xl shadow-sm hover:bg-red-50 hover:text-red-600 text-slate-500 transition-all flex items-center justify-center">
                                <span class="material-icons text-lg">close</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Subir Nueva Imagen</label>
                    <input type="file" name="image" id="image-input" accept="image/*"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-colors cursor-pointer">
                </div>
            </div>

            <div class="flex items-center ml-1 bg-slate-50 p-4 rounded-2xl border border-slate-100 w-max mb-6">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                    {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                    class="h-4 w-4 text-[#FF6600] border-gray-300 rounded focus:ring-[#FF6600]">
                <label for="is_featured" class="ml-3 block text-[10px] font-black text-slate-800 uppercase tracking-widest cursor-pointer">
                    Marcar como Evento Destacado
                </label>
            </div>
            
            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-[#FF6600] text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-900/30 flex items-center justify-center gap-2">
                    Actualizar Evento <span class="material-icons text-sm">save</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script de Vista Previa --}}
<script>
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