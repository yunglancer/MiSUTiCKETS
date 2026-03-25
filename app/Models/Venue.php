<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; // 1. IMPORTAR EL CONTRATO DE AUDITORÍA

class Venue extends Model implements Auditable // 2. IMPLEMENTAR LA INTERFAZ
{
    use \OwenIt\Auditing\Auditable; // 3. ACTIVAR EL TRAIT DE AUDITORÍA

    // 'capacity' incluido para permitir guardar el aforo total del recinto
    protected $fillable = ['name', 'city', 'address', 'capacity'];

    /**
     * Relación: Un recinto tiene muchos eventos.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Relación: Un recinto tiene muchas zonas físicas (VIP, General, etc.)
     * Esta es la relación clave para validar el aforo (Lógica de Elías).
     */
    public function zones()
    {
        return $this->hasMany(VenueZone::class);
    }
}