<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;    
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
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
        // 1. Validación
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'required|exists:venues,id',       
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Draft,Published,Cancelled',
        ]);

        // 2. Manejo de la imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // 3. Crear el evento con datos dinámicos
        Event::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(100, 999),
            'description' => $request->description,
            'event_date' => $request->event_date,
            'image_path' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'status' => $request->status,
            'category_id' => $request->category_id,
            'venue_id' => $request->venue_id,
        ]);

        return redirect()->route('admin.events.index')->with('success', '¡Evento creado con éxito!');
    }
}