<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- NUEVO: Importar el Trait de Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // <-- NUEVO: Agregar HasRoles aquí

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   protected $fillable = [
        'name',
        'email',
        'password',
        'document_id',
        'phone',
        'role', // <--- Agrega esta línea
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Un usuario tiene muchas órdenes de compra
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Un usuario tiene muchos tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}