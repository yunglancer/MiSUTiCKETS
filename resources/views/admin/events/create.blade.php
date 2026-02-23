@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Crear Nuevo Evento</h1>
        <a href="{{ route('admin.events.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Volver
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-8">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Título del Evento</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Concierto de Rock" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lugar (Venue)</label>
                    <select name="venue_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">Seleccione un lugar</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha y Hora</label>
                    <input type="datetime-local" name="event_date" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Draft">Borrador</option>
                        <option value="Published">Publicado</option>
                        <option value="Cancelled">Cancelado</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Imagen de Portada</label>
                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="is_featured" class="ml-2 block text-sm text-gray-900 font-bold">Marcar como Destacado</label>
            </div>

            <div class="flex justify-end pt-6 border-t">
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg">
                    Guardar Evento 🚀
                </button>
            </div>
        </form>
    </div>
</div>
@endsection