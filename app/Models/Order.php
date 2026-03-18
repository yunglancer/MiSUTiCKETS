<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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