<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 
use OwenIt\Auditing\Contracts\Auditable; // <--- 1. IMPORTAR EL CONTRATO DE AUDITORÍA

class User extends Authenticatable implements Auditable // <--- 2. IMPLEMENTAR LA INTERFAZ
{
    use HasFactory, Notifiable, HasRoles; 
    use \OwenIt\Auditing\Auditable; // <--- 3. ACTIVAR EL "ESPÍA" (TRAIT)

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
        'role', 
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

    // Un usuario (organizador) puede tener muchos eventos
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}