<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class PaymentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfContent;

    public function __construct(Order $order, $pdfContent)
    {
        $this->order = $order;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ ¡Pago Aprobado! Aquí tienes tus entradas - MiSUTiCKETS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_approved', 
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, "Entradas_Orden_#{$this->order->order_number}.pdf")
                    ->withMime('application/pdf'),
        ];
    }
}