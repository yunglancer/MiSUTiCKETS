<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAMOS EL CONTRATO DE AUDITORÍA

class EventZone extends Model implements Auditable // <--- 2. IMPLEMENTAMOS LA INTERFAZ
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAMOS EL "ESPÍA" (TRAIT)

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