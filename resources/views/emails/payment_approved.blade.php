<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1e293b; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 20px; }
        .header { text-align: center; padding-bottom: 20px; }
        .logo { color: #FF6600; font-weight: 900; font-size: 24px; text-transform: uppercase; }
        .btn { background-color: #FF6600; color: white; padding: 12px 25px; text-decoration: none; border-radius: 12px; font-weight: bold; display: inline-block; margin-top: 20px; }
        .footer { font-size: 12px; color: #94a3b8; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">MiSU<span style="color: #1e293b;">TICKETS</span></div>
        </div>
        
        <h2 style="color: #0f172a;">¡Hola, {{ $order->user->name }}!</h2>
        <p>¡Grandes noticias! Tu pago por la orden <strong>#{{ $order->order_number }}</strong> ha sido verificado y aprobado con éxito.</p>
        
        <p>Adjunto a este correo encontrarás un archivo PDF con tus entradas digitales. Cada entrada tiene un código QR único que será escaneado en la puerta del evento.</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px;"><strong>Resumen de tu compra:</strong></p>
            <p style="margin: 5px 0; font-size: 13px;">Monto: REF. {{ number_format($order->total_amount, 2) }}</p>
            <p style="margin: 5px 0; font-size: 13px;">Método: {{ str_replace('_', ' ', $order->payment_method) }}</p>
        </div>

        <p>Te recomendamos descargar el PDF y tenerlo listo en tu teléfono (o impreso) al momento de llegar. ¡Nos vemos en el evento!</p>

        <div class="footer">
            <p>Este es un mensaje automático de la plataforma MiSUTiCKETS.<br>
            Universidad Bicentenaria de Aragua.</p>
        </div>
    </div>
</body>
</html>