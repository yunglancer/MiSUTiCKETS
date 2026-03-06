<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket; // <--- Importante para poder buscar la entrada en la base de datos
use Barryvdh\DomPDF\Facade\Pdf; // <--- La magia que convierte el HTML en PDF

class ClientController extends Controller
{
    public function dashboard()
    {
        // 1. Identificamos quién es el usuario logueado
        $user = Auth::user();

        // 2. Buscamos sus tickets y de paso nos traemos los datos del evento y la orden
        // 'latest()' los ordena del más nuevo al más viejo
        $tickets = $user->tickets()->with(['event', 'order'])->latest()->get();

        // 3. Buscamos su historial de compras
        $orders = $user->orders()->latest()->get();

        // 4. Mandamos todo eso a tu vista del panel
        return view('client.dashboard', compact('tickets', 'orders'));
    }

    // ==========================================
    // 🖨️ NUEVA FUNCIÓN: Descargar la entrada en PDF
    // ==========================================
    public function downloadTicket($id)
    {
        // 1. Buscamos el ticket exacto. 
        // El 'where' asegura que nadie pueda descargar un ticket que no haya comprado (Seguridad vital).
        $ticket = Ticket::with(['event.venue', 'user'])
                        ->where('user_id', Auth::id())
                        ->findOrFail($id);

        // 2. Cargamos la vista del diseño (ticket-pdf.blade.php) y le pasamos los datos del ticket
        $pdf = Pdf::loadView('client.ticket-pdf', compact('ticket'));

        // 3. Generamos un nombre dinámico para el archivo (Ej: Entrada_MiSUTiCKETS_A7B9X2KL.pdf)
        $fileName = 'Entrada_MiSUTiCKETS_' . strtoupper(substr($ticket->ticket_code, 0, 8)) . '.pdf';
        
        // 4. Forzamos la descarga en el navegador
        return $pdf->download($fileName);
    }
}