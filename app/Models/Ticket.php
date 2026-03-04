<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'event_id', 'ticket_code', 'status'
    ];

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
}