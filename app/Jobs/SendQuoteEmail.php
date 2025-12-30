<?php

namespace App\Jobs;

use App\Models\Quote;
use App\Mail\QuoteRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendQuoteEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300]; // 1min, 5min

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Quote $quote
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get first media attachment (if any)
        $attachment = $this->quote->getFirstMedia('damage_photos');
        
        // Get all super admin emails
        $adminEmails = \App\Models\User::role('super_admin')->pluck('email')->toArray();
        
        // Fallback to hardcoded email if no super admins found
        if (empty($adminEmails)) {
            $adminEmails = ['ceyhan-serkan@hotmail.com'];
        }
        
        Mail::to($adminEmails)->send(
            new QuoteRequestMail(
                $this->quote->only(['company_name', 'name', 'email', 'service_type', 'message']),
                $attachment?->getPath()
            )
        );
    }
}
