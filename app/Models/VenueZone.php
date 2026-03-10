<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenueZone extends Model
{
    protected $fillable = ['venue_id', 'name'];

    // Relación inversa: Una zona pertenece a un Recinto
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}