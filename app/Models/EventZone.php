<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventZone extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'event_id',
        'venue_zone_id',
        'price',
        'capacity',
        'is_active',
    ];

    /**
     * Relación: Una zona de evento pertenece a un Evento.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relación: Una zona de evento pertenece a una zona de recinto original (VenueZone).
     * Esto nos permite obtener el nombre de la zona (ej: "VIP").
     */
    public function venueZone()
    {
        return $this->belongsTo(VenueZone::class, 'venue_zone_id');
    }
}