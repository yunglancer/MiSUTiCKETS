@extends('layouts.admin')

@section('content')
<div class="font-display max-w-5xl mx-auto">
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

    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 p-8 md:p-10">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Título del Evento</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none" 
                           placeholder="Ej: Concierto de Rock">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Categoría</label>
                    <select name="category_id" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none appearance-none">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Lugar (Venue)</label>
                    <select name="venue_id" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none appearance-none">
                        <option value="">Seleccione un lugar</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Descripción</label>
                <textarea name="description" rows="4" 
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Fecha y Hora</label>
                    <input type="datetime-local" name="event_date" required 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Estado</label>
                    <select name="status" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] text-slate-900 text-sm transition-all outline-none appearance-none">
                        <option value="Draft">Borrador</option>
                        <option value="Published">Publicado</option>
                        <option value="Cancelled">Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Imagen de Portada</label>
                <input type="file" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-colors cursor-pointer">
            </div>

            <div class="flex items-center ml-1 bg-slate-50 p-4 rounded-2xl border border-slate-100 w-max">
                <input type="checkbox" name="is_featured" id="is_featured" class="h-4 w-4 text-[#FF6600] border-gray-300 rounded focus:ring-[#FF6600]">
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
@endsection