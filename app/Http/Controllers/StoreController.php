<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class StoreController extends Controller
{
    // 1. LA LANDING PAGE (welcome) -> Muestra solo algunos eventos
    public function landing()
    {
        // Traemos solo 3 eventos para la portada que estén PUBLICADOS y DESTACADOS
        $featuredEvents = Event::with(['category', 'venue'])
                      ->where('status', 'Published')
                      ->where('is_featured', true) // <-- ¡AQUÍ ESTÁ LA CONEXIÓN CON LO DE ÁNGEL!
                      ->orderBy('event_date', 'asc')
                      ->take(3) 
                      ->get();

        // Mandamos esos eventos a la vista welcome (Landing Page)
        return view('welcome', compact('featuredEvents'));
    }

    // 2. EL CATÁLOGO COMPLETO (/eventos) -> La misión de Elías
    public function index()
    {
        // Traemos TODOS los eventos publicados con sus categorías y lugares
        $events = Event::with(['category', 'venue'])
                      ->where('status', 'Published')
                      ->orderBy('event_date', 'asc')
                      ->get();

        return view('events.index', compact('events'));
    }

    // 3. EL DETALLE DEL EVENTO (/eventos/{id}) -> La misión de Jean
    public function show($id)
    {
        $event = Event::with(['category', 'venue', 'tickets'])
                      ->where('status', 'Published')
                      ->findOrFail($id);

        // 🚨 ALERTA DE LÍDER TÉCNICO: 
        // Jean está retornando 'admin.events.show'. Asegúrate de que él guardó su 
        // diseño público ahí. Lo ideal para mantener el orden es que la vista pública 
        // del cliente esté en 'events.show' y la del admin en 'admin.events.show'.
        return view('admin.events.show', compact('event'));
    }
}