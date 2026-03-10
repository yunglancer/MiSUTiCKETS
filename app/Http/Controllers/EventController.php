<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;    
use App\Models\EventZone; // Asegúrate de tener este modelo creado
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        // Cargamos relaciones para mostrar zonas y precios sin saturar la DB
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
        // 1. Validación más permisiva para evitar el bucle de recarga
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'required|exists:venues,id',       
            'event_date' => 'required', // Quitamos 'date' temporalmente para probar
            'status' => 'required',
            // Cambiamos a nullable para que si Alpine no envía bien el array, 
            // el error no te eche hacia atrás automáticamente
            'zones' => 'nullable|array', 
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('events', 'public');
                }

                // Crear el Evento
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

                // 2. Logica de guardado de zonas mejorada
                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $zoneData) {
                        // Verificamos que sea una zona activa y que tenga datos básicos
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

                return redirect()->route('admin.events.index')->with('success', 'Evento creado.');
            });
        } catch (\Exception $e) {
            // Esto es vital: si falla, queremos ver POR QUÉ en la pantalla
            dd($e->getMessage()); 
        }
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        $venues = Venue::all();
        // Cargamos las zonas que ya tiene configuradas este evento
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
                    if ($event->image_path) {
                        Storage::disk('public')->delete($event->image_path);
                    }
                    $data['image_path'] = $request->file('image')->store('events', 'public');
                }

                $event->update($data);

                // Actualizar zonas si el formulario de edit también las incluye
                if ($request->has('zones')) {
                    // Estrategia: Sincronizar (Borrar y re-crear es lo más limpio aquí)
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
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado de la base de datos.');
    }

    public function list()
    {
        $events = Event::where('status', 'Published')->latest()->get();
        return view('events.index', compact('events'));
    }
}