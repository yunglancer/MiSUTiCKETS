<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        // Traemos los recintos paginados para mantener el orden
        $venues = Venue::latest()->paginate(10);
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
        ]);

        Venue::create($request->all());

        return redirect()->route('admin.venues.index')
            ->with('success', '¡Recinto creado con éxito!');
    }

    public function edit(Venue $venue)
    {
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'address'  => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $venue->update($request->all());

        return redirect()->route('admin.venues.index')
            ->with('success', 'Recinto actualizado correctamente.');
    }

    public function destroy(Venue $venue)
    {
        // Verificar si hay eventos asociados antes de borrar
        if ($venue->events()->exists()) {
            return redirect()->route('admin.venues.index')
                ->with('error', 'No se puede eliminar el recinto porque tiene eventos asociados.');
        }

        $venue->delete();

        return redirect()->route('admin.venues.index')
            ->with('success', 'Recinto eliminado.');
    }
}