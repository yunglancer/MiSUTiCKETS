<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // 1. Esta función MUESTRA la pantalla de pago (El Cajero)
    public function show(Event $event)
    {
        // Le pasamos los datos del evento a la vista de checkout
        return view('client.checkout', compact('event'));
    }

    // 2. Esta función PROCESA la compra cuando el cliente le da a "Pagar"
    public function process(Request $request)
    {
        // 1. Validamos los datos básicos que nos enviará el formulario
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'payment_reference' => 'required|string',
            'total_amount' => 'required|numeric'
        ]);

        try {
            // Iniciamos la "Cápsula de Seguridad": Si algo falla adentro, Laravel deshace todo mágicamente
            DB::beginTransaction();

            $user = Auth::user();

            // 2. Creamos la Orden de Compra (La factura)
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'MISU-' . strtoupper(Str::random(8)), // Ej: MISU-A7B9X2KL
                'total_amount' => $request->total_amount,
                'status' => 'pending', // Queda pendiente hasta que se valide el Pago Móvil
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
            ]);

            // 3. Generamos los Tickets exactos según la cantidad que pidió el cliente (Bucle)
            for ($i = 0; $i < $request->quantity; $i++) {
                Ticket::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'event_id' => $request->event_id,
                    'ticket_code' => Str::uuid(), // Genera un código largo y único para el QR
                    'status' => 'active',
                ]);
            }

            // 4. Si llegamos hasta aquí sin errores, guardamos todo permanentemente en la base de datos
            DB::commit();

            // 5. Lo mandamos de regreso al panel de cliente con un mensaje de éxito
            return redirect()->route('client.dashboard')->with('success', '¡Compra procesada con éxito! Tus entradas están en revisión.');

        } catch (\Exception $e) {
            // Si la base de datos de Aiven falla, deshacemos todo y le avisamos al cliente
            DB::rollBack();
            return back()->with('error', 'Hubo un problema al procesar tu compra. Por favor, intenta de nuevo.');
        }
    }
}