<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; 
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAMOS EL CONTRATO DE AUDITORÍA

class Event extends Model implements Auditable // <--- 2. IMPLEMENTAMOS LA INTERFAZ
{
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAMOS EL "ESPÍA" (TRAIT)

    // Agregué 'user_id' aquí para que no te dé error al crear eventos
    protected $fillable = [
        'category_id', 'venue_id', 'user_id', 'title', 'slug', 'description', 
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

    // Relación: Un evento pertenece a un usuario (el creador/organizador)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}