<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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