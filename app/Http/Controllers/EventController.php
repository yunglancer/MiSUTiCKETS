<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;    
use App\Models\EventZone;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
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
        $user = Auth::user();
        $query = Event::with(['category', 'venue', 'user'])
            ->withCount(['tickets as sold_tickets' => function($q) {
                $q->whereHas('order', function($o) {
                    $o->where('status', 'paid');
                });
            }]);

        if ($user && !$user->hasRole('SuperAdmin')) {
            $query->where('user_id', Auth::id());
        }

        $events = $query->latest()->paginate(10);
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
            'image' => 'nullable|image|max:10240',
        ]);

        $currentUserId = Auth::id();

        try {
            return DB::transaction(function () use ($request, $currentUserId) {
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $cloudinary = $this->getCloudinaryInstance();
                    $upload = $cloudinary->uploadApi()->upload(
                        $request->file('image')->getRealPath(),
                        ['folder' => 'misutickets_events']
                    );
                    $imagePath = $upload['secure_url'];
                }

                $event = new Event();
                $event->user_id = $currentUserId;
                $event->title = $request->title;
                $event->slug = Str::slug($request->title) . '-' . time();
                $event->description = $request->description;
                $event->event_date = $request->event_date;
                $event->image_path = $imagePath;
                $event->is_featured = $request->has('is_featured');
                $event->status = $request->status;
                $event->category_id = $request->category_id;
                $event->venue_id = $request->venue_id;
                $event->save();

                if ($request->has('zones') && is_array($request->zones)) {
                    foreach ($request->zones as $zoneData) {
                        if (isset($zoneData['venue_zone_id']) && isset($zoneData['price'])) {
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

                return redirect()->route('admin.events.index')->with('success', '¡Evento creado con éxito!');
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear evento: ' . $e->getMessage());
        }
    }

    public function edit(Event $event)
    {
        if (!auth()->user()->hasRole('SuperAdmin') && $event->user_id !== auth()->id()) {
            return abort(403, 'No tienes permiso para editar este evento.');
        }

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
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        if (!auth()->user()->hasRole('SuperAdmin') && $event->user_id !== auth()->id()) {
            return abort(403, 'No tienes permiso para eliminar este evento.');
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado.');
    }

    // Muestra métricas en el ADMIN
    public function show(Event $event)
    {
        $user = auth()->user();
        if (!$user->hasRole('SuperAdmin') && $event->user_id !== $user->id) {
            abort(403);
        }

        $statsByZone = $event->venue->zones->map(function ($zone) use ($event) {
            $soldTickets = Ticket::where('event_id', $event->id)
                                ->where('venue_zone_id', $zone->id)
                                ->whereHas('order', function($q) {
                                    $q->where('status', 'paid');
                                })->count();

            return [
                'name' => $zone->name,
                'capacity' => $zone->capacity,
                'sold' => $soldTickets,
                'percentage' => ($zone->capacity > 0) ? ($soldTickets * 100 / $zone->capacity) : 0,
                'revenue' => $soldTickets * $zone->price 
            ];
        });

        $totalRevenue = $statsByZone->sum('revenue');
        $totalSold = $statsByZone->sum('sold');

        return view('admin.events.metrics', compact('event', 'statsByZone', 'totalRevenue', 'totalSold'));
    }

    // --- CORREGIDO: Apunta a la vista admin.events.show ---
    public function showPublic($id)
    {
        $event = Event::with(['venue.zones', 'category', 'eventZones'])->findOrFail($id);
        // Cambié 'events.show' por 'admin.events.show' que es la que existe en tu proyecto
        return view('admin.events.show', compact('event'));
    }

    // Lista eventos con FILTROS en el FRONT
    public function list(Request $request)
    {
        $query = Event::with(['venue', 'category'])
                      ->where('status', 'Published');

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where('title', 'LIKE', "%{$buscar}%");
        }

        if ($request->filled('categoria')) {
            $categoriaNombre = $request->input('categoria');
            $query->whereHas('category', function($q) use ($categoriaNombre) {
                $q->where('name', $categoriaNombre);
            });
        }

        $events = $query->latest()->get();
        $categories = Category::all(); 
        
        // Esta vista suele estar en resources/views/events/index.blade.php
        return view('events.index', compact('events', 'categories'));
    }
}