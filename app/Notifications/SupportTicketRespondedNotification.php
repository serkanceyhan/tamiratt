<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketRespondedNotification extends Notification
{
    use Queueable;

    public function __construct(public SupportTicket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Destek Talebiniz Yanıtlandı: ' . $this->ticket->ticket_number)
            ->line('Destek talebinize yanıt verildi.')
            ->line('Ticket No: ' . $this->ticket->ticket_number)
            ->line('Konu: ' . $this->ticket->subject)
            ->line('')
            ->line('Admin Yanıtı:')
            ->line($this->ticket->admin_response)
            ->action('Talebi Görüntüle', url('/customer/support-tickets'))
            ->line('Sorunuz çözüldüyse lütfen geri bildirimde bulunun.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'admin_response' => $this->ticket->admin_response,
        ];
    }
}
