<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAR EL CONTRATO DE AUDITORÍA

class Venue extends Model implements Auditable // <--- 2. IMPLEMENTAR LA INTERFAZ
{
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAR EL "ESPÍA" (TRAIT)

    protected $fillable = ['name', 'city', 'address', 'capacity'];

    /**
     * Relación: Un recinto tiene muchos eventos.
     * (Corregido de venue() a events() por convención de Laravel)
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    /**
     * Relación: Un recinto tiene muchas zonas originales (VIP, General, etc.)
     */
    public function zones()
    {
        return $this->hasMany(VenueZone::class);
    }
}