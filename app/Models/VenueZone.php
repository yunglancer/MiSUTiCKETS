<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class VenueZone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // Agregamos 'capacity' a la lista de campos permitidos
    protected $fillable = [
        'venue_id', 
        'name', 
        'capacity'
    ];

    /**
     * Relación inversa: Una zona pertenece a un Recinto
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}