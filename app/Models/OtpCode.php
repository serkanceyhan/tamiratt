<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OtpCode extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'verified_at',
        'attempts',
        'purpose',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Purpose constants
    const PURPOSE_PHONE_VERIFICATION = 'phone_verification';
    const PURPOSE_LOGIN = 'login';
    const PURPOSE_PASSWORD_RESET = 'password_reset';

    // Max attempts before lockout
    const MAX_ATTEMPTS = 3;
    
    // OTP validity in minutes
    const VALIDITY_MINUTES = 5;

    /**
     * Generate a new OTP code for the given phone
     */
    public static function generateFor(string $phone, string $purpose = self::PURPOSE_PHONE_VERIFICATION): self
    {
        // Invalidate any existing codes for this phone/purpose
        self::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        return self::create([
            'phone' => $phone,
            'code' => self::generateCode(),
            'expires_at' => now()->addMinutes(self::VALIDITY_MINUTES),
            'purpose' => $purpose,
            'attempts' => 0,
            'created_at' => now(),
        ]);
    }

    /**
     * Generate random 6-digit code
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify an OTP code
     */
    public static function verify(string $phone, string $code, string $purpose = self::PURPOSE_PHONE_VERIFICATION): bool
    {
        $otp = self::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '<', self::MAX_ATTEMPTS)
            ->latest('created_at')
            ->first();

        if (!$otp) {
            return false;
        }

        // Increment attempts
        $otp->increment('attempts');

        if ($otp->code !== $code) {
            return false;
        }

        // Mark as verified
        $otp->update(['verified_at' => now()]);

        return true;
    }

    /**
     * Check if phone is locked out due to too many attempts
     */
    public static function isLockedOut(string $phone, string $purpose = self::PURPOSE_PHONE_VERIFICATION): bool
    {
        return self::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '>=', self::MAX_ATTEMPTS)
            ->exists();
    }

    /**
     * Check if a new code can be sent (rate limiting)
     */
    public static function canSendNewCode(string $phone, string $purpose = self::PURPOSE_PHONE_VERIFICATION): bool
    {
        $lastCode = self::where('phone', $phone)
            ->where('purpose', $purpose)
            ->latest('created_at')
            ->first();

        if (!$lastCode) {
            return true;
        }

        // Allow new code after 60 seconds
        return $lastCode->created_at->addSeconds(60)->isPast();
    }

    /**
     * Get seconds until a new code can be sent
     */
    public static function secondsUntilNewCode(string $phone, string $purpose = self::PURPOSE_PHONE_VERIFICATION): int
    {
        $lastCode = self::where('phone', $phone)
            ->where('purpose', $purpose)
            ->latest('created_at')
            ->first();

        if (!$lastCode) {
            return 0;
        }

        $canSendAt = $lastCode->created_at->addSeconds(60);
        
        if ($canSendAt->isPast()) {
            return 0;
        }

        return now()->diffInSeconds($canSendAt);
    }

    // Scopes

    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('verified_at')
                    ->where('expires_at', '>', now())
                    ->where('attempts', '<', self::MAX_ATTEMPTS);
    }
}
