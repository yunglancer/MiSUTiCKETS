<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable; // 1. IMPORTAR EL CONTRATO DE AUDITORÍA

class VenueZone extends Model implements Auditable // 2. IMPLEMENTAR LA INTERFAZ
{
    use \OwenIt\Auditing\Auditable; // 3. ACTIVAR EL TRAIT DE AUDITORÍA

    // Agregamos 'capacity' para permitir el registro del aforo físico por zona
    protected $fillable = [
        'venue_id', 
        'name', 
        'capacity'
    ];

    /**
     * Relación inversa: Una zona pertenece a un Recinto (Venue)
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}