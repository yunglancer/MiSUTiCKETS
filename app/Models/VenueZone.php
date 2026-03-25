<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class VenueZone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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