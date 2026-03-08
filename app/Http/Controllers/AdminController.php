<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Calculamos el dinero total recaudado
        // Fíjate que ahora dice 'total_amount'
        $totalRevenue = Order::sum('total_amount');

        // 2. Contamos cuántas entradas se han emitido
        $ticketsSold = Ticket::count();

        // 3. Contamos cuántos eventos están publicados actualmente
        $activeEvents = Event::where('status', 'Published')->count();

        // 4. Traemos las últimas 5 compras para mostrarlas en una tabla rápida
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 5. Retornamos la vista que te pasé en el mensaje anterior
        return view('admin.dashboard', compact('totalRevenue', 'ticketsSold', 'activeEvents', 'recentOrders'));
    }
    // 1. Muestra la pantalla del escáner con el resultado del ticket
    public function verifyTicket($id)
    {
        // Buscamos la entrada en la base de datos junto con su evento y el usuario que la compró
        $ticket = Ticket::with(['order.user', 'event'])->findOrFail($id);

        return view('admin.tickets.verify', compact('ticket'));
    }

    // 2. El botón que presiona el portero para "quemar" la entrada y dejar pasar al cliente
    public function markTicketAsUsed($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status === 'used') {
            return back()->with('error', '¡ALERTA! Esta entrada ya fue utilizada anteriormente.');
        }

        // Si está válida, la marcamos como usada
        $ticket->update(['status' => 'used']);

        return back()->with('success', '¡Entrada validada correctamente! El cliente puede pasar.');
    }
}