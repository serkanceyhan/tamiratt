<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketCreatedNotification extends Notification
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
            ->subject('Yeni Destek Talebi: ' . $this->ticket->ticket_number)
            ->line('Yeni bir destek talebi oluşturuldu.')
            ->line('Ticket No: ' . $this->ticket->ticket_number)
            ->line('Kategori: ' . $this->getCategoryLabel($this->ticket->category))
            ->line('Konu: ' . $this->ticket->subject)
            ->action('Talebi Görüntüle', url('/admin/support-tickets/' . $this->ticket->id . '/edit'))
            ->line('24 saat içinde yanıt vermeyi unutmayın.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'subject' => $this->ticket->subject,
            'category' => $this->ticket->category,
        ];
    }
    
    private function getCategoryLabel(string $category): string
    {
        return match($category) {
            'service' => 'Hizmet',
            'payment' => 'Ödeme',
            'technical' => 'Teknik',
            default => $category,
        };
    }
}
