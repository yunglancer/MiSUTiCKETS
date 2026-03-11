<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\EventZone;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
    
        // 1. Definimos las consultas base
        $revenueQuery = Order::where('status', 'paid');
        $ticketsQuery = Ticket::query();
        $eventsQuery = Event::where('status', 'Published');
        $ordersQuery = Order::with('user');

        // 2. FILTRO DE SEGURIDAD: Si NO es SuperAdmin, filtramos por sus eventos
        if (!$user->hasRole('SuperAdmin')) {
            $userId = $user->id;

            // Ingresos: Solo de órdenes que tengan tickets de sus eventos
            $revenueQuery->whereHas('tickets.event', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });

            // Tickets: Solo los emitidos para sus eventos
            $ticketsQuery->whereHas('event', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });

            // Eventos: Solo los que él creó
            $eventsQuery->where('user_id', $userId);

            // Órdenes Recientes: Solo las que contienen tickets de sus eventos
            $ordersQuery->whereHas('tickets.event', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        // 3. Ejecutamos los cálculos
        $totalRevenue = $revenueQuery->sum('total_amount');
        $ticketsSold  = $ticketsQuery->count();
        $activeEvents = $eventsQuery->count();
        $recentOrders = $ordersQuery->latest()->take(5)->get();

        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'ticketsSold'  => $ticketsSold,
            'activeEvents' => $activeEvents,
            'recentOrders' => $recentOrders
        ]);
    }

    public function verifyTicket($id)
    {
        $ticket = Ticket::with(['order.user', 'event'])->findOrFail($id);
        return view('admin.tickets.verify', compact('ticket'));
    }

public function markTicketAsUsed($id)
{
    $ticket = Ticket::findOrFail($id);

    if ($ticket->status === 'used') {
        return response()->json(['success' => false, 'message' => '¡ALERTA! Esta entrada ya fue usada.'], 422);
    }

    $ticket->update([
        'status' => 'used',
        'validated_at' => now(), // Guardamos la hora exacta de entrada
    ]);

    return response()->json(['success' => true, 'message' => 'Entrada validada. ¡Bienvenidos a MiSUJEVA!']);
}
    public function pendingOrders()
    {
        $orders = Order::with('user')
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('admin.orders.pending', compact('orders'));
    }

    public function approveOrder(Order $order)
    {
        // 🚀 CAMBIO CLAVE: De 'approved' a 'paid' para que coincida con tu ENUM
        $updated = $order->update(['status' => 'paid']);

        if ($updated) {
            return redirect()->route('admin.orders.pending')
                             ->with('success', "¡Orden #{$order->order_number} marcada como PAGADA!");
        }

        return back()->with('error', 'No se pudo actualizar el estado de la orden.');
    }

    public function rejectOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            // Usamos 'cancelled' que es el valor válido en tu migración
            $order->update(['status' => 'cancelled']);

            // Devolvemos el inventario a las zonas correspondientes
            $ticketsPorZona = Ticket::where('order_id', $order->id)
                                ->get()
                                ->groupBy('event_zone_id');

            foreach ($ticketsPorZona as $zoneId => $tickets) {
                if ($zoneId) { // Verificamos que el ticket tenga zona asignada
                    $cantidadADevolver = $tickets->count();
                    EventZone::where('id', $zoneId)->increment('capacity', $cantidadADevolver);
                }
                
                // Limpiamos los tickets de esa orden fallida
                Ticket::where('order_id', $order->id)->delete(); 
            }

            DB::commit();
            return back()->with('error', 'Pago rechazado. Los cupos han vuelto a estar disponibles.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el rechazo: ' . $e->getMessage());
        }
    }
}