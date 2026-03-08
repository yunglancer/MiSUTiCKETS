<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;    
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        
        $categories = Category::all();
        $venues = Venue::all();
        
        return view('admin.events.create', compact('categories', 'venues'));
    }

    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'required|exists:venues,id',       
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Draft,Published,Cancelled',
        ]);

        // 2. Manejo de la imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // 3. Crear el evento con datos dinámicos
        Event::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(100, 999),
            'description' => $request->description,
            'event_date' => $request->event_date,
            'image_path' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'status' => $request->status,
            'category_id' => $request->category_id,
            'venue_id' => $request->venue_id,
        ]);

        return redirect()->route('admin.events.index')->with('success', '¡Evento creado con éxito!');
    }

        public function edit(Event $event)
    {
        $categories = Category::all();
        $venues = Venue::all();
        return view('admin.events.edit', compact('event', 'categories', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'required|exists:venues,id',
            'event_date' => 'required|date',
            'status' => 'required|in:Draft,Published,Cancelled',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . $event->id;
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            // Borrar imagen antigua si existe
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', '¡Evento actualizado!');
    }

    public function destroy(Event $event)
    {
        // Borrar la imagen del disco antes de eliminar el registro
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado correctamente.');
    }

    // Esta función es para la cartelera pública de Elías
public function list()
{
    // Traemos todos los eventos publicados
    $events = Event::where('status', 'Published')->latest()->get();

    // Enviamos los datos a la vista que modificamos antes
    return view('events.index', compact('events'));
}

}