<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenueController extends Controller
{
    public function index()
    {
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
            'zones.*.name'     => 'required|string|max:255',
            'zones.*.capacity' => 'required|integer|min:0',
        ]);

        // --- VALIDACIÓN DE SUMA (Lógica de Elías) ---
        $sumaCapacidades = 0;
        foreach ($request->zones as $zoneData) {
            $sumaCapacidades += (int)$zoneData['capacity'];
        }

        if ($sumaCapacidades !== (int)$request->capacity) {
            return back()->withInput()->with('error', 
                "Error de Aforo: La suma de las zonas ($sumaCapacidades) debe ser igual a la capacidad total ({$request->capacity}).");
        }

        try {
            return DB::transaction(function () use ($request) {
                $venue = Venue::create($request->only(['name', 'city', 'address', 'capacity']));

                foreach ($request->zones as $zoneData) {
                    $venue->zones()->create([
                        'name'     => $zoneData['name'],
                        'capacity' => $zoneData['capacity']
                    ]);
                }

                return redirect()->route('admin.venues.index')
                    ->with('success', '¡Recinto y zonas creados con éxito!');
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear recinto: ' . $e->getMessage());
        }
    }

    public function edit(Venue $venue)
    {
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
            'zones.*.name'     => 'required|string|max:255',
            'zones.*.capacity' => 'required|integer|min:0',
        ]);

        // --- VALIDACIÓN DE SUMA (Lógica de Elías aplicada a edición) ---
        $sumaCapacidades = 0;
        foreach ($request->zones as $zoneData) {
            $sumaCapacidades += (int)$zoneData['capacity'];
        }

        if ($sumaCapacidades !== (int)$request->capacity) {
            return back()->withInput()->with('error', 
                "Error de Aforo: La suma de las zonas ($sumaCapacidades) debe ser igual a la capacidad total ({$request->capacity}).");
        }

        try {
            return DB::transaction(function () use ($request, $venue) {
                // 1. Actualizamos datos básicos del recinto
                $venue->update($request->only(['name', 'city', 'address', 'capacity']));

                // 2. Sincronizamos zonas: Borramos las anteriores y creamos las nuevas
                $venue->zones()->delete(); 

                foreach ($request->zones as $zoneData) {
                    $venue->zones()->create([
                        'name'     => $zoneData['name'],
                        'capacity' => $zoneData['capacity']
                    ]);
                }

                return redirect()->route('admin.venues.index')
                    ->with('success', 'Recinto y zonas actualizados correctamente.');
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function getZones(Venue $venue)
    {
        return response()->json($venue->zones);
    }

    public function destroy(Venue $venue)
    {
        if ($venue->events()->exists()) {
            return redirect()->route('admin.venues.index')
                ->with('error', 'No se puede eliminar el recinto porque tiene eventos asociados.');
        }

        $venue->delete();
        return redirect()->route('admin.venues.index')->with('success', 'Recinto eliminado.');
    }

    public function show(Venue $venue)
    {
        return redirect()->route('admin.venues.index');
    }
}