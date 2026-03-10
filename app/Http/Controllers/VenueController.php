<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        // Traemos los recintos paginados para mantener el orden
        $venues = Venue::with('zones')->latest()->paginate(10);
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'address'  => 'required|string',
            'capacity' => 'required|integer|min:1',
            'zones'    => 'required|array|min:1',
            'zones.*'  => 'required|string|max:255',
        ]);

        // 1. Creamos el recinto
        $venue = Venue::create($request->only(['name', 'city', 'address', 'capacity']));

        // 2. Guardamos las zonas
        foreach ($request->zones as $zoneName) {
            $venue->zones()->create([
                'name' => $zoneName
            ]);
        }

        return redirect()->route('admin.venues.index')
            ->with('success', '¡Recinto creado con éxito!');
    }

    public function edit(Venue $venue)
    {
        // Cargamos las zonas para asegurarnos de que estén disponibles en la vista
        $venue->load('zones');
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'address'  => 'required|string',
            'capacity' => 'required|integer|min:1',
            'zones'    => 'required|array|min:1',
            'zones.*'  => 'required|string|max:255',
        ]);

        // 1. Actualizamos datos básicos del recinto
        $venue->update($request->only(['name', 'city', 'address', 'capacity']));

        // 2. Sincronizamos zonas: Borramos las anteriores y creamos las nuevas
        $venue->zones()->delete(); 

        foreach ($request->zones as $zoneName) {
            $venue->zones()->create([
                'name' => $zoneName
            ]);
        }

        return redirect()->route('admin.venues.index')
            ->with('success', 'Recinto actualizado correctamente.');
    }
    public function getZones(Venue $venue)
    {
        // Esto es lo que Alpine.js lee para mostrar las filas de Precio/Capacidad
        return response()->json($venue->zones);
    }

    public function destroy(Venue $venue)
    {
        // Verificar si hay eventos asociados antes de borrar
        if ($venue->events()->exists()) {
            return redirect()->route('admin.venues.index')
                ->with('error', 'No se puede eliminar el recinto porque tiene eventos asociados.');
        }

        // Al borrar el recinto, las zonas se borran automáticamente 
        // por el "onDelete('cascade')" que pusimos en la migración.
        $venue->delete();

        return redirect()->route('admin.venues.index')
            ->with('success', 'Recinto eliminado.');
    }
}