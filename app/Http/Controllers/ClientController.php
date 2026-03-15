<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket; 
use App\Models\Order; 
use Barryvdh\DomPDF\Facade\Pdf; 

class ClientController extends Controller
{
    public function dashboard()
    {
        // Traemos eventZone.venueZone para obtener el nombre de la zona y evitar errores de diseño
        $tickets = Ticket::with(['event', 'order', 'eventZone.venueZone'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        $orders = Order::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('client.dashboard', compact('tickets', 'orders'));
    }

    public function downloadTicket($id)
    {
        // 1. Buscamos el ticket cargando explícitamente la zona del evento para obtener el precio
        $ticket = Ticket::with([
            'event.venue', 
            'eventZone.venueZone', 
            'user', 
            'order'
        ])->findOrFail($id);

        // 2. Seguridad básica
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para descargar esta entrada.');
        }

        // 3. Validación de pago
        if (!$ticket->order || $ticket->order->status !== 'paid') {
            abort(403, 'Tu pago aún está en revisión o no existe. Podrás descargar tu entrada cuando sea validada.');
        }

        // 4. Generamos el PDF (Asegúrate de pasar la variable 'ticket')
        $pdf = Pdf::loadView('client.tickets.pdf', compact('ticket'));
        
        // 5. Descargamos
        return $pdf->download('MiSUTiCKETS_' . ($ticket->ticket_code ?? $ticket->id) . '.pdf');
    }
}