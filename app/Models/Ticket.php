<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAMOS EL CONTRATO DE AUDITORÍA

class Ticket extends Model implements Auditable // <--- 2. IMPLEMENTAMOS LA INTERFAZ
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAMOS EL "ESPÍA" (TRAIT)

    protected $fillable = [
        'order_id',
        'user_id',
        'event_id',
        'event_zone_id', // <--- ¡Asegúrate de que este exista!
        'ticket_code',
        'status',
    ];

    /**
     * SEGURIDAD: Solo mostrar tickets que pertenezcan a eventos del Organizador
     */
    protected static function booted()
    {
        static::addGlobalScope('ticket_access', function ($builder) {
        if (!auth()->check()) return;

        if (request()->is('admin/*')) {
            // Organizador: ve tickets de sus eventos
            $builder->whereHas('event', function ($q) {
                $q->where('user_id', auth()->id());
            });
            } else {
                // Cliente: ve tickets de las órdenes que él pagó
                $builder->whereHas('order', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            }
        });
    }

    // Un ticket pertenece a una orden
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Un ticket pertenece a un usuario (el dueño)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un ticket pertenece a un evento
    public function event()
    {
        return $this->belongsTo(Event::class); // Asegúrate de que el modelo Event exista
    }
    
    /**
     * Relación: Un ticket pertenece a una Zona de Evento específica.
     */
    public function eventZone()
    {
        return $this->belongsTo(EventZone::class);
    }
}