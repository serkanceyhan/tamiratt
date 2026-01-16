<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'user_type',
        'category',
        'provider_id',
        'service_request_id',
        'provider_offer_id',
        'subject',
        'description',
        'attachments',
        'status',
        'priority',
        'admin_response',
        'assigned_to',
        'responded_at',
        'resolved_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function providerOffer()
    {
        return $this->belongsTo(ProviderOffer::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Helper methods
    public static function generateTicketNumber()
    {
        $year = now()->year;
        $lastTicket = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastTicket ? (int) substr($lastTicket->ticket_number, -5) + 1 : 1;

        return 'TS-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->ticket_number) {
                $ticket->ticket_number = static::generateTicketNumber();
            }
        });
        
        static::created(function ($ticket) {
            // Notify admins about new ticket
            $admins = \App\Models\User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\SupportTicketCreatedNotification($ticket));
            }
        });
        
        static::updating(function ($ticket) {
            // If admin response is added, notify the user
            if ($ticket->isDirty('admin_response') && filled($ticket->admin_response)) {
                $ticket->user->notify(new \App\Notifications\SupportTicketRespondedNotification($ticket));
                $ticket->responded_at = now();
            }
        });
    }
}
