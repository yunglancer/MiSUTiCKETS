<?php

namespace App\Http\Controllers;

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
        // Cargamos el evento junto con sus zonas para no saturar la base de datos
        $event->load('eventZones.venueZone');
        return view('client.checkout', compact('event'));
    }

    // =========================================================================
    // 2. GENERA EL CARRITO Y MUESTRA LA PASARELA DE PAGO (Paso 2)
    // =========================================================================
    public function summary(Request $request)
    {
        // Validamos que envíen el ID del evento y el array de tickets
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
        ]);

        $event = Event::with('eventZones.venueZone')->findOrFail($request->event_id);
        
        $cartItems = [];
        $subtotal = 0;
        $totalTickets = 0;

        // Recorremos el array de tickets [zone_id => cantidad]
        foreach ($request->tickets as $zoneId => $quantity) {
            if ($quantity > 0) {
                $zone = $event->eventZones->where('id', $zoneId)->first();
                
                // Validamos que la zona exista y tenga suficiente capacidad
                if ($zone && $zone->capacity >= $quantity) {
                    $itemTotal = $zone->price * $quantity;
                    $subtotal += $itemTotal;
                    $totalTickets += $quantity;
                    
                    // Guardamos la info estructurada para la vista
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

        // Si manipularon el HTML y no seleccionaron nada
        if ($totalTickets == 0) {
            return back()->with('error', 'Debes seleccionar al menos una entrada.');
        }

        // LÓGICA DE NEGOCIO: Impuestos y Fees (Ejemplo: 10% de fee de plataforma)
        $platformFee = $subtotal * 0.10; 
        $grandTotal = $subtotal + $platformFee;

        // Mandamos los datos a la nueva vista de resumen
        return view('client.summary', compact('event', 'cartItems', 'subtotal', 'platformFee', 'grandTotal', 'totalTickets'));
    }

    // =========================================================================
    // 3. EL BÚNKER: PROCESA LA COMPRA EN LA BASE DE DATOS (Paso 3)
    // =========================================================================
    public function process(Request $request)
    {
        // Validamos la petición final
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'cart_items' => 'required|string', // Lo recibiremos como JSON desde la vista summary
            'payment_method' => 'required|string',
            'payment_reference' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $cartItems = json_decode($request->cart_items, true);
            
            $subtotal = 0;
            $lockedZones = [];

            // 1. RECALCULAMOS TOTALES Y BLOQUEAMOS LAS FILAS (Para evitar sobreventas)
            foreach ($cartItems as $item) {
                // lockForUpdate() bloquea la fila en Aiven hasta que termine la transacción
                $zone = EventZone::where('id', $item['zone_id'])
                                ->lockForUpdate()
                                ->firstOrFail();
                
                // Doble chequeo de capacidad en tiempo real
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

            // 2. CREAMOS LA ORDEN (Factura)
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'MISU-' . strtoupper(Str::random(8)),
                'total_amount' => $grandTotal, // NUNCA confiamos en el front, usamos el cálculo del backend
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
                        'status' => 'active', // <--- ¡AQUÍ ESTABA EL BUG! Debe decir 'active'
                    ]);
                }
                
                // Descontamos la capacidad real de la zona
                $lz['model']->decrement('capacity', $lz['quantity']);
            }

            DB::commit();
            return redirect()->route('client.dashboard')->with('success', '¡Compra procesada con éxito! Tus entradas están en revisión.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Regresamos al cliente a la página anterior con el mensaje de error normal
            return redirect()->route('checkout.show', $request->event_id)->with('error', 'Hubo un problema: ' . $e->getMessage());
        }
    }
}