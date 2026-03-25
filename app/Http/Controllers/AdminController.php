<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\EventZone;
use Illuminate\Support\Facades\DB;

// 🚀 IMPORTACIONES NUEVAS PARA EL PDF Y LOS CORREOS
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentApproved;
use Illuminate\Support\Facades\Log;

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
            $ordersQuery = Order::with(['user', 'tickets.event']);
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

    // =========================================================================
    // 🚀 APROBAR ORDEN: CAMBIA STATUS, GENERA PDF Y ENVÍA CORREO
    // =========================================================================
    public function approveOrder(Order $order)
    {
        // 1. Cambiamos el estado de la orden a pagada
        $updated = $order->update(['status' => 'paid']);

        if ($updated) {
            
            // 2. Cargamos las relaciones necesarias para que la vista del PDF no explote
            $order->load(['user', 'tickets.event', 'tickets.eventZone.venueZone']);
            
            try {
                // 3. Generamos UN SOLO PDF con todas las entradas de esa orden
                $pdf = Pdf::loadView('client.tickets.ticket_multiple', ['order' => $order]);
                $pdfContent = $pdf->output();

                // 4. Enviamos el correo con el PDF adjunto de manera silenciosa
                Mail::to($order->user->email)->send(new PaymentApproved($order, $pdfContent));
                
            } catch (\Exception $e) {
                // Si el correo falla (ej. se cayó el internet del servidor), igual se aprueba el pago.
                // Registramos el error en el log para saber qué pasó.
                Log::error("Orden {$order->order_number} aprobada, pero falló el envío del PDF a {$order->user->email}: " . $e->getMessage());
            }

            return redirect()->route('admin.orders.pending')
                             ->with('success', "¡Orden #{$order->order_number} marcada como PAGADA y entradas enviadas por correo!");
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