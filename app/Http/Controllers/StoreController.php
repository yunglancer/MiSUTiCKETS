<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class StoreController extends Controller
{
    // 1. LA LANDING PAGE (welcome) -> Muestra solo algunos eventos
    public function landing()
    {
        // Traemos solo 3 eventos para la portada (puedes filtrar por 'is_featured' si Ángel agregó ese campo)
        $featuredEvents = Event::with(['category', 'venue'])
                      ->where('status', 'Published')
                      ->orderBy('event_date', 'asc')
                      ->take(3) // Solo manda 3 para no saturar la landing
                      ->get();

        // Mandamos esos 3 eventos a la vista welcome (Landing Page)
        return view('welcome', compact('featuredEvents'));
    }

    // 2. EL CATÁLOGO COMPLETO (/eventos) -> La misión de Elías
    public function index()
    {
        // Aquí sí traemos TODOS los eventos publicados
        /* ESTA PARTE SE COMENTA PARA PODER USAR DATOS QUEMADOS SIN ERRORES DE BASE DE DATOS
        $events = Event::with(['category', 'venue'])
                      ->where('status', 'Published')
                      ->orderBy('event_date', 'asc')
                      ->get();
        */

        // Cambiamos el return original para que no pida la variable $events que acabamos de comentar
        return view('events.index');
    }

    // 3. EL DETALLE DEL EVENTO (/eventos/{id}) -> La misión de Jean
    public function show($id)
    {
        $event = Event::with(['category', 'venue'])
                      ->where('status', 'Published')
                      ->findOrFail($id);

        return view('admin.events.show', compact('event'));
    }
}