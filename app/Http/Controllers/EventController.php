<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;    
use App\Models\EventZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
// IMPORTANTE: Importamos el SDK nativo de Cloudinary
use Cloudinary\Cloudinary;

class EventController extends Controller
{
    // Función privada para no repetir código de conexión
    private function getCloudinaryInstance()
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => 'duw8vlhwx',
                'api_key'    => '971486582559871',
                'api_secret' => 'IWIQxbCjYBU0nAAs-xpMEDJgHBs',
            ],
        ]);
    }

    public function index()
    {
        $events = Event::with(['category', 'venue', 'eventZones.venueZone'])
                        ->latest()
                        ->paginate(10);
                        
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
            'event_date' => 'required', 
            'status' => 'required',
            'zones' => 'nullable|array', 
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
                $imagePath = null;
                if ($request->hasFile('image')) {
                    // CONEXIÓN DIRECTA NATIVA
                    $cloudinary = $this->getCloudinaryInstance();
                    $upload = $cloudinary->uploadApi()->upload(
                        $request->file('image')->getRealPath(),
                        ['folder' => 'misutickets_events']
                    );
                    $imagePath = $upload['secure_url'];
                }

                $event = Event::create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title) . '-' . time(),
                    'description' => $request->description,
                    'event_date' => $request->event_date,
                    'image_path' => $imagePath,
                    'is_featured' => $request->has('is_featured'),
                    'status' => $request->status,
                    'category_id' => $request->category_id,
                    'venue_id' => $request->venue_id,
                ]);

                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $zoneData) {
                        if (isset($zoneData['is_active']) && isset($zoneData['price'])) {
                            EventZone::create([
                                'event_id' => $event->id,
                                'venue_zone_id' => $zoneData['venue_zone_id'],
                                'price' => $zoneData['price'] ?? 0,
                                'capacity' => $zoneData['capacity'] ?? 0,
                                'is_active' => true,
                            ]);
                        }
                    }
                }

                return redirect()->route('admin.events.index')->with('success', 'Evento creado con éxito en la nube.');
            });
        } catch (\Exception $e) {
            dd("Error en Store: " . $e->getMessage()); 
        }
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        $venues = Venue::all();
        $event->load('eventZones'); 
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
        ]);

        try {
            return DB::transaction(function () use ($request, $event) {
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
                    // CONEXIÓN DIRECTA NATIVA
                    $cloudinary = $this->getCloudinaryInstance();
                    $upload = $cloudinary->uploadApi()->upload(
                        $request->file('image')->getRealPath(),
                        ['folder' => 'misutickets_events']
                    );
                    $data['image_path'] = $upload['secure_url'];
                }
                
                $event->update($data);

                if ($request->has('zones')) {
                    EventZone::where('event_id', $event->id)->delete();
                    foreach ($request->zones as $zoneData) {
                        if (isset($zoneData['is_active'])) {
                            EventZone::create([
                                'event_id' => $event->id,
                                'venue_zone_id' => $zoneData['venue_zone_id'],
                                'price' => $zoneData['price'],
                                'capacity' => $zoneData['capacity'],
                                'is_active' => true,
                            ]);
                        }
                    }
                }

                return redirect()->route('admin.events.index')->with('success', '¡Evento actualizado correctamente!');
            });
        } catch (\Exception $e) {
            dd("Error en Update: " . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado.');
    }

    public function list(Request $request)
    {
        // Iniciamos la consulta base con relaciones para optimizar carga
        $query = Event::with(['venue', 'category'])
                      ->where('status', 'Published');

        // Filtrar por término de búsqueda (Input 'buscar')
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where('title', 'LIKE', "%{$buscar}%");
        }

        // Filtrar por categoría (Parámetro 'categoria' en URL)
        if ($request->filled('categoria')) {
            $categoriaNombre = $request->input('categoria');
            $query->whereHas('category', function($q) use ($categoriaNombre) {
                $q->where('name', $categoriaNombre);
            });
        }

        $events = $query->latest()->get();
        $categories = Category::all(); 
        
        return view('events.index', compact('events', 'categories'));
    }
}