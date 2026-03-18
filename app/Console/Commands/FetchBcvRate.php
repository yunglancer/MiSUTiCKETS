<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class FetchBcvRate extends Command
{
    // El nombre que usarás en la terminal para llamarlo
    protected $signature = 'bcv:fetch';
    protected $description = 'Consulta la API para obtener la tasa del BCV y la guarda en la base de datos';

    public function handle()
    {
        $this->info('🤖 Iniciando consulta de la tasa BCV...');

        try {
            // 🚀 INTENTO 1: Usando DolarApi (Rápida y estable)
            $response1 = Http::timeout(10)->get('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if ($response1->successful() && $response1->json('promedio')) {
                $rate = $response1->json('promedio');
                $this->saveRate($rate, 'DolarApi');
                return Command::SUCCESS;
            }

            // 🛡️ INTENTO 2 (Respaldo): Usando PyDolarVenezuela
            $this->warn('⚠️ DolarApi no respondió. Intentando con PyDolarVenezuela...');
            $response2 = Http::timeout(10)->get('https://pydolarve.org/api/v1/dollar?page=bcv');

            if ($response2->successful()) {
                $data = $response2->json();
                if (isset($data['monitors']['bcv']['price'])) {
                    $rate = $data['monitors']['bcv']['price'];
                    $this->saveRate($rate, 'PyDolarVenezuela');
                    return Command::SUCCESS;
                }
            }

            $this->error('❌ No se pudo obtener la tasa de ninguna API.');
            return Command::FAILURE;

        } catch (\Exception $e) {
            $this->error('❌ Error de conexión: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    // Función para actualizar la tabla Settings que ya creamos
    private function saveRate($rate, $source)
    {
        // Formateamos para asegurar que sea numérico y con 2 decimales
        $rate = number_format((float) $rate, 2, '.', '');

        Setting::updateOrCreate(
            ['key' => 'bcv_rate'],
            ['value' => $rate]
        );

        $this->info("✅ ¡Éxito! Tasa BCV actualizada a: Bs. {$rate} (Fuente: {$source})");
    }
}