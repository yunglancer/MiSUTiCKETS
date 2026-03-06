<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Entrada MiSUTiCKETS</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .ticket-container { border: 2px dashed #FF6600; border-radius: 15px; padding: 30px; margin: 0 auto; max-width: 600px; }
        .header { background-color: #0f172a; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin-top: -30px; margin-left: -30px; margin-right: -30px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 28px; letter-spacing: 2px; }
        .text-orange { color: #FF6600; }
        .event-title { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        .info-row { margin-bottom: 10px; font-size: 14px; }
        .label { color: #64748b; font-weight: bold; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .value { font-size: 16px; font-weight: bold; color: #0f172a; }
        .divider { border-top: 1px solid #e2e8f0; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; padding: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; }
        .uuid { font-family: monospace; font-size: 12px; letter-spacing: 1px; color: #64748b; margin-top: 10px;}
    </style>
</head>
<body>

    <div class="ticket-container">
        <div class="header">
            <h1>MISU<span class="text-orange">TICKETS</span></h1>
            <p style="margin: 5px 0 0 0; font-size: 12px; letter-spacing: 3px; color: #cbd5e1;">ENTRADA OFICIAL</p>
        </div>

        <div class="event-title text-orange">
            {{ $ticket->event->title }}
        </div>

        <div class="info-row">
            <div class="label">Fecha y Hora</div>
            <div class="value">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d/m/Y - h:i A') }}</div>
        </div>

        <div class="info-row">
            <div class="label">Lugar del Evento</div>
            <div class="value">{{ $ticket->event->venue ? $ticket->event->venue->name : 'Por definir' }}</div>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <div class="label">Titular de la Entrada</div>
            <div class="value">{{ $ticket->user->name }}</div>
        </div>
        <div class="info-row">
            <div class="label">Documento de Identidad</div>
            <div class="value">C.I: {{ $ticket->user->document_id }}</div>
        </div>

        <div class="footer">
            <h3 style="margin: 0 0 10px 0; color: #0f172a;">ZONA DE ESCANEO</h3>
            <p style="margin: 0; font-size: 12px; color: #64748b;">Presenta este código en la puerta del evento.</p>
            <div class="uuid">
                CÓDIGO: {{ $ticket->ticket_code }}
            </div>
            <p style="margin: 10px 0 0 0; font-size: 10px; color: #FF6600; font-weight: bold;">[ Próximamente Código QR aquí ]</p>
        </div>
    </div>

</body>
</html>