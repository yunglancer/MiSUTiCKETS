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
                <label class="block text-[10px] font-black text-slate-800 uppercase tracking-widest mb-2 ml-1">Imagen Actual</label>
                @if($event->image_path)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $event->image_path) }}" class="w-48 h-32 object-cover rounded-2xl shadow-md border border-slate-200">
                    </div>
                @endif
                <input type="file" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-colors cursor-pointer">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-[#FF6600] text-white text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-slate-900/30 flex items-center justify-center gap-2">
                    Actualizar Evento <span class="material-icons text-sm">save</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection