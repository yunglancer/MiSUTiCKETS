<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAMOS EL CONTRATO DE AUDITORÍA

class Order extends Model implements Auditable // <--- 2. IMPLEMENTAMOS LA INTERFAZ
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAMOS EL "ESPÍA" (TRAIT)

    // Los campos que podemos guardar
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status', // <--- ¡ESTE DEBE ESTAR AQUÍ!
        'payment_method',
        'payment_reference',
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
}