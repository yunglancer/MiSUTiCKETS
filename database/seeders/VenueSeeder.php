<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Estadio José Pérez Colmenares',
                'city' => 'Maracay',
                'address' => 'Calle Campo Elías, Barrio San Rosa',
                'capacity' => 12650,
            ],
            [
                'name' => 'Concha Acústica de Las Delicias',
                'city' => 'Maracay',
                'address' => 'Av. Las Delicias',
                'capacity' => 4000,
            ],
            [
                'name' => 'Estadio Monumental Simón Bolívar',
                'city' => 'Caracas',
                'address' => 'La Rinconada',
                'capacity' => 38000,
            ],
            [
                'name' => 'Teatro Teresa Carreño',
                'city' => 'Caracas',
                'address' => 'Paseo Colón, Bellas Artes',
                'capacity' => 2400,
            ],
            [
                'name' => 'La Hamburgeseria',
                'city' => 'Maracay',
                'address' => 'Parque Carlos Raul Villanueva, Av. Las Delicias',
                'capacity' => 500,
            ],
            [
                'name' => 'Tonka By Botanicals Drinks',
                'city' => 'Maracay',
                'address' => 'Parque Carlos Raul Villanueva, Av. Las Delicias',
                'capacity' => 400,
            ]
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}