<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket; 
use Barryvdh\DomPDF\Facade\Pdf; 

class ClientController extends Controller
{
    public function dashboard()
    {
        // 1. Identificamos quién es el usuario logueado
        $user = Auth::user();

        // 2. Buscamos sus tickets y de paso nos traemos los datos del evento y la orden
        $tickets = $user->tickets()->with(['event', 'order'])->latest()->get();

        // 3. Buscamos su historial de compras
        $orders = $user->orders()->latest()->get();

        // 4. Mandamos todo eso a tu vista del panel
        return view('client.dashboard', compact('tickets', 'orders'));
    }

    public function downloadTicket($id)
    {
        // 1. Buscamos el ticket con sus relaciones
        $ticket = Ticket::with(['event.venue', 'eventZone.venueZone', 'user', 'order'])->findOrFail($id);

        // 2. Seguridad: Nadie puede descargar el ticket de otra persona
        if ($ticket->user_id !== auth()->id()) {
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