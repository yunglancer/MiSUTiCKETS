<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los roles que definiste
        $roleAdmin = Role::create(['name' => 'SuperAdmin']);
        $roleOrganizador = Role::create(['name' => 'Organizador']);
        $roleValidador = Role::create(['name' => 'Validador']);
        $roleCliente = Role::create(['name' => 'Cliente']);

        // 2. Crear un usuario Administrador por defecto
        $admin = User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin@misutickets.com',
            'password' => Hash::make('password123'),
            'document_id' => 'V-00000000',
            'phone' => '0000-0000000'
        ]);

        // 3. Asignarle el rol de SuperAdmin
        $admin->assignRole($roleAdmin);
    }
}