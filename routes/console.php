<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\EventZone;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// =========================================================================
// 🛡️ CAPA DE SEGURIDAD 3: LIMPIADOR AUTOMÁTICO DE INVENTARIO
// =========================================================================
Schedule::call(function () {
    // 1. Buscamos órdenes que sigan 'pending' después de 2 horas
    $ordenesVencidas = Order::where('status', 'pending')
                            ->where('created_at', '<', now()->subHours(2))
                            ->get();

    foreach ($ordenesVencidas as $order) {
        DB::transaction(function () use ($order) {
            // 2. Marcamos la orden como cancelada (por tiempo expirado)
            $order->update(['status' => 'cancelled']);
            
            // 3. Agrupamos los tickets y le devolvemos la capacidad a la zona
            $ticketsPorZona = Ticket::where('order_id', $order->id)->get()->groupBy('event_zone_id');
            
            foreach ($ticketsPorZona as $zoneId => $tickets) {
                if ($zoneId) {
                    EventZone::where('id', $zoneId)->increment('capacity', $tickets->count());
                }
            }
            
            // 4. Borramos los tickets fantasma de la base de datos
            Ticket::where('order_id', $order->id)->delete();
        });
    }
})->hourly(); // Se ejecuta automáticamente cada hora

// =========================================================================
// 💸 ACTUALIZADOR AUTOMÁTICO DE LA TASA BCV
// =========================================================================
Schedule::command('bcv:fetch')->twiceDaily(8, 16); // Se ejecuta a las 8:00 AM y 4:00 PM