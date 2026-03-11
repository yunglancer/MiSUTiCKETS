<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket; 
use App\Models\Order; // <--- Faltaba importar el modelo Order
use Barryvdh\DomPDF\Facade\Pdf; 

class ClientController extends Controller
{
    public function dashboard()
    {
        // 1. Buscamos los tickets directamente en la tabla de Tickets (Método anti-fallos)
        // Traemos eventZone.venueZone para que no explote tu diseño al buscar el nombre de la zona
        $tickets = Ticket::with(['event', 'order', 'eventZone.venueZone'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        // 2. Buscamos las compras directamente en la tabla de Órdenes
        $orders = Order::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        // 3. Mandamos todo eso a tu vista del panel
        return view('client.dashboard', compact('tickets', 'orders'));
    }

    public function downloadTicket($id)
    {
        // 1. Buscamos el ticket con sus relaciones
        $ticket = Ticket::with(['event.venue', 'eventZone.venueZone', 'user', 'order'])->findOrFail($id);

        // 2. Seguridad: Nadie puede descargar el ticket de otra persona
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para descargar esta entrada.');
        }

        // 3. 🔒 EL BÚNKER: Validamos que la orden diga 'paid'
        if ($ticket->order->status !== 'paid') {
            abort(403, 'Tu pago aún está en revisión. Podrás descargar tu entrada cuando el administrador verifique la transferencia.');
        }

        // 4. Generamos el PDF
        $pdf = Pdf::loadView('client.tickets.pdf', compact('ticket'));
        
        // 5. Descargamos con un nombre único
        return $pdf->download('MiSUTiCKETS_' . $ticket->ticket_code . '.pdf');
    }
}