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
        // 1. Calculamos los ingresos sumando solo lo que está marcado como 'paid'
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        
        // 2. Variable para la vista (mantenemos el nombre que pide tu dashboard.blade)
        $ticketsSold = Ticket::count(); 
        
        $activeEvents = Event::where('status', 'Published')->count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

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
            return back()->with('error', '¡ALERTA! Esta entrada ya fue utilizada anteriormente.');
        }

        $ticket->update(['status' => 'used']);
        return back()->with('success', '¡Entrada validada correctamente! El cliente puede pasar.');
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