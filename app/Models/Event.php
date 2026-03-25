<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; 
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