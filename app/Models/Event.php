<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; 
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class Event extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'category_id', 
        'venue_id', 
        'user_id', 
        'title', 
        'slug', 
        'description', 
        'image_path', 
        'hero_path',  // Nombre real en tu DB
        'flyer_path', // Nombre real en tu DB
        'event_date', 
        'is_featured', 
        'status'
    ];
    
    /**
     * SEGURIDAD CRÍTICA: Global Scope para Organizadores
     */
    protected static function booted()
    {
        static::addGlobalScope('solo_mis_eventos', function (Builder $builder) {
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
    // ACCESORES MODERNOS (Para obtener las URLs listas)
    // =========================================================================
    
    // Imagen Principal (Miniatura)
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl($this->image_path));
    }

    // Imagen Hero (Banner Superior)
    protected function heroUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl($this->hero_path, 'Hero'));
    }

    // Imagen Flyer (Poster Lateral)
    protected function flyerUrl(): Attribute
    {
        return Attribute::get(fn () => $this->resolveImageUrl($this->flyer_path, 'Flyer'));
    }

    /**
     * Lógica común para resolver URLs de imágenes
     */
    private function resolveImageUrl($path, $placeholder = 'Evento')
    {
        if (!$path) {
            // Placeholder elegante si no hay imagen
            return "https://placehold.co/1200x600/1e293b/FFFFFF?text={$placeholder}";
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . $path);
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}