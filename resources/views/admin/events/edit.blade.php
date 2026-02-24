@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Editar Evento: {{ $event->title }}</h1>

    <div class="bg-white shadow-md rounded-lg p-8">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lugar</label>
                    <select name="venue_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                <textarea name="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                    <input type="datetime-local" name="event_date" value="{{ date('Y-m-d\TH:i', strtotime($event->event_date)) }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Draft" {{ $event->status == 'Draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="Published" {{ $event->status == 'Published' ? 'selected' : '' }}>Publicado</option>
                        <option value="Cancelled" {{ $event->status == 'Cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Imagen Actual</label>
                @if($event->image_path)
                    <img src="{{ asset('storage/' . $event->image_path) }}" class="w-32 h-20 object-cover mb-2 rounded">
                @endif
                <input type="file" name="image" class="block w-full text-sm text-gray-500">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">
                    Actualizar Evento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection