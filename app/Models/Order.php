<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAMOS EL CONTRATO DE AUDITORÍA

class Order extends Model implements Auditable // <--- 2. IMPLEMENTAMOS LA INTERFAZ
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAMOS EL "ESPÍA" (TRAIT)

protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'exchange_rate', // <--- ¡AQUÍ ESTÁ EL NUEVO!
        'status', 
        'payment_method',
        'payment_reference',
        // ¡ESTOS SON LOS NUEVOS!
        'payment_name',
        'payment_document',
        'payment_phone',
        'payment_receipt_path',
    ];

    /**
     * SEGURIDAD CRÍTICA: Solo mostrar órdenes de los eventos del Organizador
     */
    protected static function booted()
    {
        static::addGlobalScope('access_control', function ($builder) {
        if (!auth()->check()) return;

        if (request()->is('admin/*')) {
            // Lógica de Organizador: Ve órdenes de sus eventos
            if (!auth()->user()->hasRole('SuperAdmin')) {
                $builder->whereHas('tickets.event', function($q) {
                    $q->where('user_id', auth()->id());
                });
            }
            } else {
                // Lógica de Cliente: Ve sus propias compras
                $builder->where('user_id', auth()->id());
            }
        });
    }

    // Una orden pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una orden tiene muchos tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    // Asegúrate de importar esto arriba si no está: 
    // use Illuminate\Database\Eloquent\Casts\Attribute;

    /**
     * Obtener el total en Bolívares calculándolo con la tasa guardada.
     */
    protected function totalBs(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_amount * $this->exchange_rate,
        );
    }
}