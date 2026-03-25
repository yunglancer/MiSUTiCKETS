<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; 
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAMOS EL CONTRATO DE AUDITORÍA

class Event extends Model implements Auditable // <--- 2. IMPLEMENTAMOS LA INTERFAZ
{
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAMOS EL "ESPÍA" (TRAIT)

    // Agregué 'user_id' aquí para que no te dé error al crear eventos
    protected $fillable = [
        'category_id', 'venue_id', 'user_id', 'title', 'slug', 'description', 
        'image_path', 'event_date', 'is_featured', 'status'
    ];
    
    /**
     * SEGURIDAD CRÍTICA: Global Scope para Organizadores
     */
    protected static function booted()
    {
        static::addGlobalScope('solo_mis_eventos', function ($builder) {
            // 1. Si no hay nadie logueado, permitimos que vea la cartelera (invitados)
            if (!auth()->check()) {
                return;
            }

            // 2. REGLA DE ORO: Si la URL NO empieza por /admin, es la vista del cliente.
            // En la cartelera pública queremos que el cliente vea TODOS los eventos disponibles.
            if (!request()->is('admin/*') && !request()->is('admin')) {
                return; 
            }

            // 3. Si es SuperAdmin dentro del panel, también ve todo
            if (auth()->user()->hasRole('SuperAdmin')) {
                return;
            }

            // 4. Si es un Organizador DENTRO del panel (/admin/...), solo ve lo suyo
            $builder->where('user_id', auth()->id());
        });
    }

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