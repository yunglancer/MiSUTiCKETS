<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['user_id', 'category_id', 'venue_id', 'title', 'slug', 'description', 'image_path', 'event_date', 'is_featured', 'status'];
    
    // Un evento pertenece a un organizador (Usuario)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    // Relación: Un evento pertenece a una categoría
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Relación: Un evento se realiza en un lugar (venue)
    public function venue() {
        return $this->belongsTo(Venue::class);
    }
    
    // Relación: Un evento tiene muchos tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Relación de Ángel: Un evento tiene muchas zonas (VIP, General, etc.) con sus precios
    public function eventZones()
    {
        return $this->hasMany(EventZone::class);
    }
    /**
     * ACCESOR INTELIGENTE PARA LA IMAGEN (Cloudinary / Local / Fallback)
     * Se usa en las vistas llamando a: $event->image_url
     */
    public function getImageUrlAttribute()
    {
        // 1. Si el evento no tiene imagen, devolvemos un fondo gris elegante
        if (!$this->image_path) {
            return 'https://ui-avatars.com/api/?name=Evento&background=f1f5f9&color=94a3b8&size=512';
        }

        // 2. Si la ruta ya es un link de internet (como la nube de Cloudinary)
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // 3. Si es una imagen vieja guardada en el disco duro local
        return asset('storage/' . $this->image_path);
    }
    
}