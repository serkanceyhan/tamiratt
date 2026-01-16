<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SupportTicket;

class AutoCloseTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close old support tickets and mark unresponded tickets as urgent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Çözüldü durumunda 1 gün sonra kapatılır
        $resolvedClosed = SupportTicket::where('status', 'resolved')
            ->where('resolved_at', '<=', now()->subDay())
            ->update([
                'status' => 'closed',
            ]);

        $this->info("Çözüldü durumunda {$resolvedClosed} ticket kapatıldı");

        // 2. Kullanıcı Bekleniyor durumunda 2 gün sonra kapatılır
        $waitingClosed = SupportTicket::where('status', 'waiting_user')
            ->where('updated_at', '<=', now()->subDays(2))
            ->update([
                'status' => 'closed',
            ]);

        $this->info("Kullanıcı bekleniyor durumunda {$waitingClosed} ticket kapatıldı");

        // 3. Bekleyen durumunda 5 gün yanıt yoksa Urgent olarak işaretlenir
        $markedUrgent = SupportTicket::where('status', 'pending')
            ->whereNull('responded_at')
            ->where('created_at', '<=', now()->subDays(5))
            ->where('priority', '!=', 'urgent')
            ->update([
                'priority' => 'urgent',
            ]);

        $this->info("5 günden uzun bekleyen {$markedUrgent} ticket urgent olarak işaretlendi");

        $this->info('Ticket otomasyonu tamamlandı!');
        
        return 0;
    }
}
