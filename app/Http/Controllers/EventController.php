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
            'zones' => 'required|array', 
            'image' => 'nullable|image|max:10240',
        ]);

        // --- LÓGICA DE ELÍAS: VALIDACIÓN DE AFORO ---
        $venue = Venue::with('zones')->find($request->venue_id);
        $totalTicketsEvento = 0;

        foreach ($request->zones as $zoneData) {
            // Solo validamos si la zona viene con datos de capacidad
            if (isset($zoneData['venue_zone_id']) && isset($zoneData['capacity'])) {
                $zonaReal = $venue->zones->where('id', $zoneData['venue_zone_id'])->first();
                $capacidadSolicitada = (int)$zoneData['capacity'];

                // 1. Validar que la zona del evento no supere la capacidad física de la zona del recinto
                if ($capacidadSolicitada > $zonaReal->capacity) {
                    return back()->withInput()->with('error', "Error de Aforo: La zona '{$zonaReal->name}' solo tiene capacidad para {$zonaReal->capacity} personas físicas. No puedes asignar {$capacidadSolicitada} tickets.");
                }
                
                $totalTicketsEvento += $capacidadSolicitada;
            }
        }

        // 2. Validar que la suma de todas las zonas no supere el aforo total del recinto
        if ($totalTicketsEvento > $venue->capacity) {
            return back()->withInput()->with('error', "Error Global: El recinto tiene un aforo máximo de {$venue->capacity}, pero tu selección de zonas suma {$totalTicketsEvento}.");
        }

        try {
            return DB::transaction(function () use ($request) {
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
                $event->user_id = Auth::id();
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

                foreach ($request->zones as $zoneData) {
                    if (isset($zoneData['venue_zone_id']) && ($zoneData['capacity'] ?? 0) > 0) {
                        EventZone::create([
                            'event_id' => $event->id,
                            'venue_zone_id' => $zoneData['venue_zone_id'],
                            'price' => $zoneData['price'] ?? 0,
                            'capacity' => $zoneData['capacity'],
                            'is_active' => true,
                        ]);
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
            'event_date' => 'required',
            'status' => 'required',
            'image' => 'nullable|image|max:10240',
        ]);

        // --- LÓGICA DE ELÍAS: VALIDACIÓN DE AFORO EN UPDATE ---
        $venue = Venue::with('zones')->find($request->venue_id);
        $totalTicketsUpdate = 0;

        if ($request->has('zones')) {
            foreach ($request->zones as $zoneData) {
                if (isset($zoneData['is_active'])) {
                    $zonaReal = $venue->zones->where('id', $zoneData['venue_zone_id'])->first();
                    $capacidadIngresada = (int)$zoneData['capacity'];

                    if ($capacidadIngresada > $zonaReal->capacity) {
                        return back()->withInput()->with('error', "Error en {$zonaReal->name}: La capacidad física es de {$zonaReal->capacity}. No puedes poner {$capacidadIngresada}.");
                    }
                    $totalTicketsUpdate += $capacidadIngresada;
                }
            }
        }

        if ($totalTicketsUpdate > $venue->capacity) {
            return back()->withInput()->with('error', "El total ($totalTicketsUpdate) excede la capacidad total permitida del recinto ({$venue->capacity}).");
        }

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

    public function show(Event $event)
    {
        $user = auth()->user();
        if (!$user->hasRole('SuperAdmin') && $event->user_id !== $user->id) {
            abort(403);
        }

        // Corregido para obtener métricas reales comparando EventZone con Tickets vendidos
        $statsByZone = $event->eventZones->map(function ($eventZone) use ($event) {
            $soldTickets = Ticket::where('event_id', $event->id)
                                ->where('venue_zone_id', $eventZone->venue_zone_id)
                                ->whereHas('order', function($q) {
                                    $q->where('status', 'paid');
                                })->count();

            return [
                'name' => $eventZone->venueZone->name,
                'capacity' => $eventZone->capacity,
                'sold' => $soldTickets,
                'percentage' => ($eventZone->capacity > 0) ? ($soldTickets * 100 / $eventZone->capacity) : 0,
                'revenue' => $soldTickets * $eventZone->price 
            ];
        });

        $totalRevenue = $statsByZone->sum('revenue');
        $totalSold = $statsByZone->sum('sold');

        return view('admin.events.metrics', compact('event', 'statsByZone', 'totalRevenue', 'totalSold'));
    }

    public function destroy(Event $event)
    {
        if (!auth()->user()->hasRole('SuperAdmin') && $event->user_id !== auth()->id()) {
            return abort(403, 'No tienes permiso para eliminar este evento.');
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado.');
    }

    // ... (Mantener métodos showPublic y list igual)
}