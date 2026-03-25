<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Venue extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // Agregamos 'capacity' al fillable para permitir guardar el aforo total
    protected $fillable = ['name', 'city', 'address', 'capacity'];

    /**
     * Relación: Un recinto tiene muchos eventos.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    /**
     * Relación: Un recinto tiene muchas zonas físicas (VIP, Gradas, etc.)
     * Esta es la relación clave para validar el aforo de Elías.
     */
    public function zones()
    {
        return $this->hasMany(VenueZone::class);
    }
}