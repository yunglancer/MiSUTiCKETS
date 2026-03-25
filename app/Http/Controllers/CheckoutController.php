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
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log; // Importante para loguear errores de correo
use Illuminate\Support\Facades\Mail; // Importante para enviar correos
use App\Mail\PaymentReported; // Importante para llamar a la clase Mailable

class CheckoutController extends Controller
{
    // =========================================================================
    // INSTANCIA DE CLOUDINARY (El método infalible del equipo)
    // =========================================================================
    private function getCloudinaryInstance()
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => 'duw8vlhwx',
                'api_key'    => '971486582559871',
                'api_secret' => 'IWIQxbCjYBU0nAAs-xpMEDJgHBs',
            ],
        ]);
    }
    // =========================================================================
    // MÉTODOS DE TAQUILLA (ADMIN/ORGANIZADOR)
    // =========================================================================

    /**
     * Muestra la lista de órdenes pendientes.
     * Gracias al Global Scope que pusimos en el Modelo Order,
     * el Organizador solo verá sus propios eventos aquí.
     */
    public function pendingOrders()
    {
        $orders = Order::with('user', 'tickets.event')
                    ->where('status', 'pending')
                    ->latest()
                    ->paginate(15);

        return view('admin.taquilla.pending', compact('orders'));
    }

    /**
     * RECHAZAR PAGO (Seguridad Crítica)
     */
    public function reject(Order $order)
    {
        // Validamos que la orden sea del organizador (Doble capa de seguridad)
        // Aunque el Global Scope ya lo hace, esto previene accidentes.
        
        try {
            return DB::transaction(function () use ($order) {
                // 1. Cambiamos el estatus a rechazado
                $order->update(['status' => 'rejected']);

                // 2. Liberamos los tickets (Devolvemos capacidad a EventZone)
                // Agrupamos por zona para hacer menos consultas a la BD
                $ticketGroups = $order->tickets->groupBy('event_zone_id');

                foreach ($ticketGroups as $zoneId => $ticketsInZone) {
                    $quantityToRelease = $ticketsInZone->count();
                    
                    $zone = EventZone::find($zoneId);
                    if ($zone) {
                        $zone->increment('capacity', $quantityToRelease);
                    }
                }

                return back()->with('success', "Orden #{$order->order_number} rechazada. Se han liberado {$order->tickets->count()} tickets al inventario.");
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el rechazo: ' . $e->getMessage());
        }
    }

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
            'payment_reference' => 'nullable|string|unique:orders,payment_reference',
        ], [
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

        // 🚀 BUSCAR LA TASA BCV REAL DE LA BD
        $settingBcv = \App\Models\Setting::where('key', 'bcv_rate')->first();
        $bcvRate = $settingBcv ? (float) $settingBcv->value : 60.50;

        return view('client.summary', compact('event', 'cartItems', 'subtotal', 'platformFee', 'grandTotal', 'totalTickets', 'bcvRate'));
    }

    // =========================================================================
    // 3. EL BÚNKER: PROCESA LA COMPRA EN LA BASE DE DATOS (Paso 3)
    // =========================================================================
    public function process(Request $request)
    {
        // 🛑 SEGURIDAD 1: Bloquear órdenes pendientes
        $ordenPendiente = Order::where('user_id', Auth::id())
                               ->where('status', 'pending')
                               ->first();

        if ($ordenPendiente) {
            return redirect()->route('checkout.show', $request->event_id)
                             ->with('error', "Ya tienes la Orden #{$ordenPendiente->order_number} en espera de pago.");
        }

        // 🛑 VALIDACIÓN BLINDADA (TODOS LOS CAMPOS)
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'cart_items' => 'required|string', 
            'payment_method' => 'required|string|in:pago_movil,zelle,binance',
            'payment_reference' => 'required|numeric|unique:orders,payment_reference',
            'payment_name' => 'required|string|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|max:100',
            'payment_document' => 'required|string|regex:/^[a-zA-Z0-9\-\.\@\_]+$/|max:100',
            'payment_phone' => 'required|string|regex:/^[\d\+\-\s]+$/|min:10|max:20',
            'payment_receipt_path' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ], [
            'payment_reference.numeric' => 'La referencia debe ser un número.',
            'payment_reference.unique' => 'Esta referencia ya fue registrada.',
            'payment_name.regex' => 'El nombre solo puede contener letras.',
            'payment_document.regex' => 'El formato del documento o correo es inválido.',
            'payment_phone.regex' => 'El teléfono solo acepta números o el signo +.',
            'payment_phone.min' => 'El teléfono debe tener al menos 10 dígitos.',
            'payment_receipt_path.required' => 'El capture es obligatorio.',
            'payment_receipt_path.image' => 'El capture debe ser una imagen válida (JPG, PNG).',
            'payment_receipt_path.max' => 'El capture no debe pesar más de 2MB.',
        ]);

        // 🚨 SI LA VALIDACIÓN FALLA: Reconstrucción Ultra-Segura
        if ($validator->fails()) {
            // 1. Recuperamos el evento
            $event = Event::with('eventZones.venueZone')->findOrFail($request->event_id);
            
            // 2. Decodificamos el carrito (Escudo Antimisiles)
            $decodedString = base64_decode($request->cart_items);
            $decodedArray = json_decode($decodedString, true);

            // Aseguramos que SIEMPRE sea un arreglo y SIEMPRE tenga llaves numéricas (0, 1, 2...)
            $cartItems = [];
            if (is_array($decodedArray)) {
                foreach ($decodedArray as $item) {
                    if (is_array($item) && isset($item['zone_id'])) {
                        $cartItems[] = $item; 
                    }
                }
            }

            // Si el carrito está vacío, regresamos al primer paso
            if (empty($cartItems)) {
                return redirect()->route('checkout.show', $request->event_id)
                                 ->with('error', 'Ocurrió un error al procesar tu carrito. Por favor, selecciona tus entradas de nuevo.');
            }

            // 3. Recalculamos TODO lo que la vista pide
            $subtotal = 0;
            $totalTickets = 0;
            foreach ($cartItems as $item) {
                $subtotal += (float) ($item['total'] ?? 0);
                $totalTickets += (int) ($item['quantity'] ?? 0);
            }
            
            $platformFee = $subtotal * 0.10;
            $grandTotal = $subtotal + $platformFee;
            
            $settingBcv = \App\Models\Setting::where('key', 'bcv_rate')->first();
            $bcvRate = $settingBcv ? (float) $settingBcv->value : 60.50;

            // 🚀 PASAMOS TODAS LAS VARIABLES
            return view('client.summary', compact(
                'event', 
                'cartItems', 
                'subtotal', 
                'platformFee', 
                'grandTotal', 
                'totalTickets', 
                'bcvRate'
            ))->withErrors($validator)->withInput();
        }

        // --- PROCESO DE GUARDADO ---
        try {
            DB::beginTransaction();

            // Subir capture
            $receiptPathUrl = null;
            if ($request->hasFile('payment_receipt_path')) {
                $cloudinary = $this->getCloudinaryInstance();
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('payment_receipt_path')->getRealPath(),
                    ['folder' => 'misutickets/receipts']
                );
                $receiptPathUrl = $upload['secure_url'];
            }

            // Limpieza del carrito para procesar tickets
            $decodedString = base64_decode($request->cart_items);
            $decodedArray = json_decode($decodedString, true);
            $cartItems = [];
            if (is_array($decodedArray)) {
                foreach ($decodedArray as $item) {
                    if (is_array($item) && isset($item['zone_id'])) {
                        $cartItems[] = $item; 
                    }
                }
            }
            
            if (empty($cartItems)) throw new \Exception("El carrito está vacío o corrupto.");
            
            $subtotal = 0;
            $lockedZones = [];

            foreach ($cartItems as $item) {
                $zone = EventZone::where('id', $item['zone_id'])->lockForUpdate()->firstOrFail();
                if ($zone->capacity < $item['quantity']) {
                    throw new \Exception("Sin cupo en la zona {$item['name']}.");
                }
                $subtotal += ($zone->price * $item['quantity']);
                $lockedZones[] = ['model' => $zone, 'quantity' => $item['quantity'], 'zone_id' => $zone->id];
            }

            $platformFee = $subtotal * 0.10;
            $grandTotal = $subtotal + $platformFee;

            $settingBcv = \App\Models\Setting::where('key', 'bcv_rate')->first();
            $currentBcvRate = $settingBcv ? (float) $settingBcv->value : 60.50;

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'MISU-' . strtoupper(Str::random(8)),
                'total_amount' => $grandTotal, 
                'exchange_rate' => $currentBcvRate,
                'status' => 'pending', 
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'payment_name' => $request->payment_name,
                'payment_document' => $request->payment_document,
                'payment_phone' => $request->payment_phone,
                'payment_receipt_path' => $receiptPathUrl,
            ]);

            foreach ($lockedZones as $lz) {
                for ($i = 0; $i < $lz['quantity']; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'event_id' => $request->event_id,
                        'event_zone_id' => $lz['zone_id'], 
                        'ticket_code' => (string) Str::uuid(),
                        'status' => 'active', 
                    ]);
                }
                $lz['model']->decrement('capacity', $lz['quantity']);
            }

            DB::commit();

            // 🚀 ENVIAR CORREO AUTOMÁTICO DE CONFIRMACIÓN
            try {
                Mail::to(Auth::user()->email)->send(new PaymentReported($order));
            } catch (\Exception $mailError) {
                Log::error("Error enviando correo de pago a " . Auth::user()->email . ": " . $mailError->getMessage());
            }

            return redirect()->route('client.dashboard')->with('success', '¡Pago reportado con éxito!');

            

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.show', $request->event_id)->with('error', $e->getMessage());
        }

        
    }
}