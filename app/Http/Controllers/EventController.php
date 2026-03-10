<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;    
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// Quitamos el Facade Storage porque ahora usaremos la nube directamente

class EventController extends Controller
{
    public function index()
    {
        // AJUSTE: Eager Loading para evitar el problema N+1 y optimizar la carga
        $events = Event::with(['category', 'venue'])->latest()->paginate(10);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'required|exists:venues,id',       
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'status' => 'required|in:Draft,Published,Cancelled',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // ☁️ MAGIA DE CLOUDINARY: Sube a la nube y devuelve el Link Seguro (HTTPS)
        $imagePath = cloudinary()->upload($request->file('image')->getRealPath(), ['folder' => 'misutickets_events'])->getSecurePath();
        }

        // AJUSTE: Generación de slug más controlada
        $event = Event::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(), // Usamos timestamp para unicidad total
            'description' => $request->description,
            'event_date' => $request->event_date,
            'image_path' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'status' => $request->status,
            'category_id' => $request->category_id,
            'venue_id' => $request->venue_id,
        ]);

        return redirect()->route('admin.events.index')->with('success', '¡Evento creado con éxito en la Nube!');
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
            'image' => 'nullable|image|max:10240',
            'is_featured' => 'boolean',
        ]);

        // AJUSTE: Mapeo manual de datos para evitar errores con $request->all()
        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'venue_id' => $request->venue_id,
            'event_date' => $request->event_date,
            'status' => $request->status,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
            'slug' => Str::slug($request->title) . '-' . $event->id,
        ];

        if ($request->hasFile('image')) {
            // ☁️ MAGIA DE CLOUDINARY: Subimos la nueva foto directo a la nube
            $data['image_path'] = $request->file('image')->storeOnCloudinary('misutickets_events')->getSecurePath();
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', '¡Evento actualizado correctamente!');
    }

    public function destroy(Event $event)
    {
        // Ya no intentamos borrar la foto del disco local de tu PC porque está en la nube.
        // Solo eliminamos el registro de la base de datos.
        $event->delete();
        
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado de la base de datos.');
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