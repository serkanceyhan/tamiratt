<?php

namespace App\Notifications;

use App\Models\Provider;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProviderApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Provider $provider
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Başvurunuz Onaylandı - Tamirat')
            ->greeting('Merhaba ' . $this->provider->company_name)
            ->line('Hizmet veren başvurunuz incelendi ve onaylandı.')
            ->line('Artık sisteme giriş yapabilir ve hizmet vermeye başlayabilirsiniz.')
            ->action('Panele Giriş Yap', url('/provider/login'))
            ->line('Aramıza hoş geldiniz!');
    }

    /**
     * Get the database/Filament representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Başvurunuz Onaylandı!')
            ->body('Hizmet veren hesabınız aktif edildi. Artık teklif vermeye başlayabilirsiniz.')
            ->success()
            ->getDatabaseMessage();
    }
}
