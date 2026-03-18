<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Entradas MiSUTiCKETS</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .ticket-container { border: 2px dashed #FF6600; border-radius: 15px; padding: 30px; margin: 0 auto; max-width: 600px; }
        .header { background-color: #0f172a; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin-top: -30px; margin-left: -30px; margin-right: -30px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 28px; letter-spacing: 2px; }
        .text-orange { color: #FF6600; }
        .event-title { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        
        .col-half { display: inline-block; width: 49%; vertical-align: top; }
        .col-full { width: 100%; }
        
        .label { color: #64748b; font-weight: bold; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; margin-bottom: 2px; }
        .value { font-size: 16px; font-weight: bold; color: #0f172a; margin-bottom: 15px; }
        .value-large { font-size: 20px; font-weight: 900; color: #FF6600; }
        
        .divider { border-top: 1px solid #e2e8f0; margin: 15px 0; }
        
        .footer { text-align: center; margin-top: 30px; padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; }
        .qr-box { margin: 15px auto; padding: 10px; background: white; display: inline-block; border-radius: 10px; border: 1px solid #e2e8f0; }
        .uuid { font-family: monospace; font-size: 11px; letter-spacing: 1px; color: #64748b; margin-top: 10px;}
        
        .text-emerald { color: #059669; }
        .text-xs { font-size: 12px; }
        
        /* 🚀 ESTA CLASE ES LA MAGIA PARA SEPARAR PÁGINAS EN EL PDF */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    @foreach($order->tickets as $ticket)
        @php
            // Cálculos dinámicos por cada ticket
            $usdPrice = $ticket->eventZone?->price ?? 0;
            $rate = $order->exchange_rate ?? 0;
            $bsPrice = $usdPrice * $rate;
            
            $paymentMethod = $order->payment_method ?? 'N/A';
            $methodDisplay = match($paymentMethod) {
                'pago_movil' => 'Pago Móvil',
                'zelle' => 'Zelle',
                'binance' => 'Binance Pay',
                default => strtoupper($paymentMethod)
            };
        @endphp

        <div class="ticket-container">
            <div class="header">
                <h1>MISU<span class="text-orange">TICKETS</span></h1>
                <p style="margin: 5px 0 0 0; font-size: 12px; letter-spacing: 3px; color: #cbd5e1;">ENTRADA OFICIAL ({{ $loop->iteration }} de {{ $order->tickets->count() }})</p>
            </div>

            <div class="event-title text-orange">
                {{ $ticket->event->title ?? 'Evento sin título' }}
            </div>

            <div class="col-half">
                <div class="label">Fecha y Hora</div>
                <div class="value">
                    {{ $ticket->event->event_date ? \Carbon\Carbon::parse($ticket->event->event_date)->format('d/m/Y - h:i A') : 'Fecha no disponible' }}
                </div>
            </div>
            <div class="col-half">
                <div class="label">Lugar del Evento</div>
                <div class="value">{{ $ticket->event->venue ? $ticket->event->venue->name : 'Por definir' }}</div>
            </div>

            <div class="divider"></div>

            <div class="col-half">
                <div class="label">Zona del Evento</div>
                <div class="value value-large">{{ $ticket->eventZone?->venueZone?->name ?? 'Zona General' }}</div>
            </div>
            <div class="col-half">
                <div class="label">Valor de la Entrada</div>
                <div class="value">
                    REF. {{ number_format($usdPrice, 2) }} 
                    <br>
                    @if($rate > 0 && $paymentMethod == 'pago_movil')
                        <span class="text-emerald text-xs" style="font-weight: 900;">Bs. {{ number_format($bsPrice, 2) }}</span>
                    @endif
                </div>
            </div>

            <div class="divider"></div>

            <div class="col-half">
                <div class="label">Orden de Compra</div>
                <div class="value">#{{ $order->order_number ?? 'N/A' }}</div>
            </div>
            <div class="col-half">
                <div class="label">Detalles de Pago</div>
                <div class="value">
                    <span style="font-size: 13px;">{{ $methodDisplay }}</span>
                    @if($rate > 0)
                        <br><span style="font-size: 10px; color: #64748b; font-weight: normal;">Tasa aplicada: Bs. {{ number_format($rate, 2) }}</span>
                    @endif
                </div>
            </div>

            <div class="divider"></div>

            <div class="col-half">
                <div class="label">Titular de la Entrada</div>
                <div class="value">{{ $order->user->name ?? 'N/A' }}</div>
            </div>
            <div class="col-half">
                <div class="label">Documento de Identidad</div>
                <div class="value">C.I: {{ $order->user->document_id ?? 'No registrado' }}</div>
            </div>

            <div class="footer">
                <h3 style="margin: 0 0 5px 0; color: #0f172a; font-size: 16px; letter-spacing: 1px;">ZONA DE ESCANEO</h3>
                <p style="margin: 0 0 10px 0; font-size: 12px; color: #64748b;">Presenta este código QR desde tu celular o impreso en puerta.</p>
                
                <div class="qr-box">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(140)->generate(route('admin.tickets.verify', $ticket->id))) }}" alt="Código QR de la Entrada">
                </div>
                
                <div class="uuid">
                    CÓDIGO ÚNICO: {{ $ticket->ticket_code ?? $ticket->id }}
                </div>
            </div>
        </div>

        {{-- Si no es el último ticket, agregamos un salto de página --}}
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach

</body>
</html>