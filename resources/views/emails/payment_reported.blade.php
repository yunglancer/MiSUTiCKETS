<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pago</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <div style="background-color: #0f172a; padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; letter-spacing: 2px;">MISU<span style="color: #FF6600;">TICKETS</span></h1>
        </div>

        <div style="padding: 30px; color: #334155;">
            <h2 style="color: #0f172a; margin-top: 0;">¡Hola, {{ $order->user->name }}! 👋</h2>
            <p style="font-size: 16px; line-height: 1.6;">Hemos recibido tu reporte de pago exitosamente. En este momento, nuestro equipo está verificando la transacción.</p>
            
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 25px 0;">
                <h3 style="margin-top: 0; color: #FF6600; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Detalles de tu Orden</h3>
                <p style="margin: 5px 0;"><strong>N° de Orden:</strong> {{ $order->order_number }}</p>
                <p style="margin: 5px 0;"><strong>Método de Pago:</strong> <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $order->payment_method) }}</span></p>
                <p style="margin: 5px 0;"><strong>Referencia:</strong> {{ $order->payment_reference }}</p>
                <p style="margin: 5px 0;"><strong>Total Pagado:</strong> REF. {{ number_format($order->total_amount, 2) }}</p>
            </div>

            <p style="font-size: 16px; line-height: 1.6;">Una vez confirmemos el pago (tarda de 1 a 12 horas), recibirás otro correo con el enlace para descargar tus entradas oficiales.</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('client.dashboard') }}" style="background-color: #FF6600; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; display: inline-block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Ver mis órdenes</a>
            </div>
        </div>

        <div style="background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b;">
            <p style="margin: 0;">¿Tienes dudas? Responde a este correo y te ayudaremos.</p>
            <p style="margin: 10px 0 0 0;">&copy; {{ date('Y') }} MiSUTiCKETS. Todos los derechos reservados.</p>
        </div>
        
    </div>

</body>
</html>