<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; padding: 30px;">
        <h1 style="color: #059669; margin-top: 0;">¡Pago Aprobado! 🎉</h1>
        <p>Hola, {{ $order->user->name }}. Hemos verificado tu pago por la Orden #{{ $order->order_number }}.</p>
        <p>Adjunto a este correo encontrarás el PDF oficial con tus entradas para el evento. ¡Nos vemos allá!</p>
    </div>
</body>
</html>