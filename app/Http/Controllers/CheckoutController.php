<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\EventZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // =========================================================================
    // 1. MUESTRA LA PANTALLA DE SELECCIÓN DE ENTRADAS (Paso 1)
    // =========================================================================
    public function show(Event $event)
    {
        $event->load('eventZones.venueZone');
        return view('client.checkout', compact('event'));
    }

    // =========================================================================
    // 2. GENERA EL CARRITO Y MUESTRA LA PASARELA DE PAGO (Paso 2)
    // =========================================================================
    public function summary(Request $request)
    {
        // 🛡️ CAPA DE SEGURIDAD PREVENTIVA: 
        // No dejamos que el usuario entre al carrito si ya tiene una orden pendiente.
        $ordenPendiente = Order::where('user_id', Auth::id())
                               ->where('status', 'pending')
                               ->first();

        if ($ordenPendiente) {
            return redirect()->route('checkout.show', $request->event_id)
                             ->with('error', "No puedes iniciar una nueva compra porque ya tienes la Orden #{$ordenPendiente->order_number} pendiente de pago.");
        }
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
          // 🛑 CAPA DE SEGURIDAD 2: Referencia única en toda la tabla 'orders'
    'payment_reference' => 'nullable|string|unique:orders,payment_reference',
], [
    // Mensaje de error personalizado para que el cliente entienda
    'payment_reference.unique' => '¡Alerta! Este número de referencia ya fue utilizado en otra compra. Si crees que es un error, contáctanos.'
]);

        $event = Event::with('eventZones.venueZone')->findOrFail($request->event_id);
        
        $cartItems = [];
        $subtotal = 0;
        $totalTickets = 0;

        foreach ($request->tickets as $zoneId => $quantity) {
            if ($quantity > 0) {
                $zone = $event->eventZones->where('id', $zoneId)->first();
                
                if ($zone && $zone->capacity >= $quantity) {
                    $itemTotal = $zone->price * $quantity;
                    $subtotal += $itemTotal;
                    $totalTickets += $quantity;
                    
                    $cartItems[] = [
                        'zone_id' => $zone->id,
                        'name' => $zone->venueZone->name ?? 'Zona General',
                        'quantity' => $quantity,
                        'price' => $zone->price,
                        'total' => $itemTotal
                    ];
                }
            }
        }

        if ($totalTickets == 0) {
            return back()->with('error', 'Debes seleccionar al menos una entrada.');
        }

        $platformFee = $subtotal * 0.10; 
        $grandTotal = $subtotal + $platformFee;

        return view('client.summary', compact('event', 'cartItems', 'subtotal', 'platformFee', 'grandTotal', 'totalTickets'));
    }

    // =========================================================================
    // 3. EL BÚNKER: PROCESA LA COMPRA EN LA BASE DE DATOS (Paso 3)
    // =========================================================================
    // =========================================================================
    // 3. EL BÚNKER: PROCESA LA COMPRA EN LA BASE DE DATOS (Paso 3)
    // =========================================================================
    public function process(Request $request)
    {
        // 🛑 CAPA DE SEGURIDAD 1: Bloquear múltiples órdenes pendientes
        $ordenPendiente = Order::where('user_id', Auth::id())
                                   ->where('status', 'pending')
                                   ->first();

        if ($ordenPendiente) {
            // 🚨 CAMBIO CLAVE: Redirigimos explícitamente al evento, nunca usamos back()
            return redirect()->route('checkout.show', $request->event_id)
                             ->with('error', "Ya tienes la Orden #{$ordenPendiente->order_number} en espera de pago. Por favor transfiere y notifica, o espera a que caduque para intentar de nuevo.");
        }

        // 🛑 CAPA DE SEGURIDAD 2: Validación manual antifraude
        // Usamos Validator::make en lugar de $request->validate() para que no haga back() automático
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'cart_items' => 'required|string', 
            'payment_method' => 'required|string',
            'payment_reference' => 'required|string|unique:orders,payment_reference',
        ], [
            'payment_reference.unique' => '¡Alerta! Este número de referencia ya fue registrado en nuestro sistema. Revisa tus datos o contáctanos si crees que es un error.'
        ]);

        // 🚨 CAMBIO CLAVE: Si hay un error (ej. referencia repetida o campo vacío), lo mandamos al evento
        if ($validator->fails()) {
            return redirect()->route('checkout.show', $request->event_id)
                             ->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $cartItems = json_decode($request->cart_items, true);
            
            $subtotal = 0;
            $lockedZones = [];

            // 1. RECALCULAMOS TOTALES Y BLOQUEAMOS LAS FILAS
            foreach ($cartItems as $item) {
                $zone = EventZone::where('id', $item['zone_id'])
                                ->lockForUpdate()
                                ->firstOrFail();
                
                if ($zone->capacity < $item['quantity']) {
                    throw new \Exception("Lo sentimos, la zona {$item['name']} ya no tiene suficientes entradas disponibles.");
                }
                
                $subtotal += ($zone->price * $item['quantity']);
                $lockedZones[] = [
                    'model' => $zone,
                    'quantity' => $item['quantity'],
                    'zone_id' => $zone->id
                ];
            }

            $platformFee = $subtotal * 0.10;
            $grandTotal = $subtotal + $platformFee;

            // 2. CREAMOS LA ORDEN
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'MISU-' . strtoupper(Str::random(8)),
                'total_amount' => $grandTotal, 
                'status' => 'pending', 
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
            ]);

            // 3. GENERAMOS LOS TICKETS Y DESCONTAMOS INVENTARIO
            foreach ($lockedZones as $lz) {
                for ($i = 0; $i < $lz['quantity']; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'event_id' => $request->event_id,
                        'event_zone_id' => $lz['zone_id'], 
                        'ticket_code' => Str::uuid(),
                        'status' => 'active', 
                    ]);
                }
                
                $lz['model']->decrement('capacity', $lz['quantity']);
            }

            DB::commit();
            return redirect()->route('client.dashboard')->with('success', '¡Compra procesada con éxito! Tus entradas están en revisión.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.show', $request->event_id)->with('error', 'Hubo un problema: ' . $e->getMessage());
        }
    }
}