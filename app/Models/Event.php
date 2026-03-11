<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; 

class Event extends Model
{
    protected $fillable = [
        'category_id', 'venue_id', 'title', 'slug', 'description', 
        'image_path', 'event_date', 'is_featured', 'status'
    ];

    // =========================================================================
    // 1. ACCESOR MODERNO (El que Laravel 12 ama)
    // =========================================================================
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            // Si no hay nada en la base de datos
            if (!$this->image_path) {
                return 'https://ui-avatars.com/api/?name=Evento&background=f1f5f9&color=94a3b8&size=512';
            }

            // Si es un link de Cloudinary u otro servicio externo
            if (str_starts_with($this->image_path, 'http')) {
                return $this->image_path;
            }

            // Si es una imagen local en storage
            return asset('storage/' . $this->image_path);
        });
    }

    // =========================================================================
    // RELACIONES
    // =========================================================================
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function venue() {
        return $this->belongsTo(Venue::class);
    }
    
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function eventZones() {
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