<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Send OTP code to phone number
     * 
     * @return array{success: bool, message: string, seconds_until_resend?: int}
     */
    public function send(string $phone, string $purpose = OtpCode::PURPOSE_PHONE_VERIFICATION): array
    {
        // Normalize phone
        $phone = app(GhostUserService::class)->normalizePhone($phone);

        // Check rate limiting
        if (!OtpCode::canSendNewCode($phone, $purpose)) {
            $seconds = OtpCode::secondsUntilNewCode($phone, $purpose);
            return [
                'success' => false,
                'message' => "Lütfen {$seconds} saniye bekleyiniz.",
                'seconds_until_resend' => $seconds,
            ];
        }

        // Check lockout
        if (OtpCode::isLockedOut($phone, $purpose)) {
            return [
                'success' => false,
                'message' => 'Çok fazla deneme yaptınız. Lütfen 5 dakika sonra tekrar deneyin.',
            ];
        }

        // Generate OTP
        $otp = OtpCode::generateFor($phone, $purpose);

        // Send SMS (Mock implementation)
        $sent = $this->sendSms($phone, $otp->code);

        if (!$sent) {
            return [
                'success' => false,
                'message' => 'SMS gönderilemedi. Lütfen daha sonra tekrar deneyin.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Doğrulama kodu gönderildi.',
            'seconds_until_resend' => 60,
        ];
    }

    /**
     * Verify OTP code
     * 
     * @return array{success: bool, message: string}
     */
    public function verify(string $phone, string $code, string $purpose = OtpCode::PURPOSE_PHONE_VERIFICATION): array
    {
        // Normalize phone
        $phone = app(GhostUserService::class)->normalizePhone($phone);

        // Check lockout
        if (OtpCode::isLockedOut($phone, $purpose)) {
            return [
                'success' => false,
                'message' => 'Çok fazla hatalı deneme yaptınız. Lütfen 5 dakika sonra tekrar deneyin.',
            ];
        }

        // Verify
        $verified = OtpCode::verify($phone, $code, $purpose);

        if (!$verified) {
            return [
                'success' => false,
                'message' => 'Doğrulama kodu hatalı veya süresi dolmuş.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Telefon numarası doğrulandı.',
        ];
    }

    /**
     * Send SMS via provider (Mock implementation)
     * 
     * In production, integrate with Netgsm, İletimerkezi, or Twilio
     */
    protected function sendSms(string $phone, string $code): bool
    {
        $message = "Ta'miratt doğrulama kodunuz: {$code}. Bu kodu kimseyle paylaşmayın.";

        // Log for development/testing
        Log::channel('daily')->info('OTP SMS', [
            'phone' => $phone,
            'code' => $code,
            'message' => $message,
        ]);

        // Mock: Always return true in development
        // In production, implement actual SMS sending here
        if (app()->environment('local', 'development', 'testing')) {
            // For development, we can also dump to console or store in session
            session()->flash('debug_otp_code', $code);
            return true;
        }

        // Production SMS implementation
        return $this->sendViaSmsProvider($phone, $message);
    }

    /**
     * Send SMS via actual provider
     * 
     * TODO: Implement actual SMS provider integration
     */
    protected function sendViaSmsProvider(string $phone, string $message): bool
    {
        $provider = config('services.sms.provider', 'mock');

        return match ($provider) {
            'netgsm' => $this->sendViaNetgsm($phone, $message),
            'iletimerkezi' => $this->sendViaIletimerkezi($phone, $message),
            'twilio' => $this->sendViaTwilio($phone, $message),
            default => true, // Mock
        };
    }

    protected function sendViaNetgsm(string $phone, string $message): bool
    {
        // TODO: Implement Netgsm API
        Log::warning('Netgsm SMS integration not implemented');
        return true;
    }

    protected function sendViaIletimerkezi(string $phone, string $message): bool
    {
        // TODO: Implement İletimerkezi API
        Log::warning('İletimerkezi SMS integration not implemented');
        return true;
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        // TODO: Implement Twilio API
        Log::warning('Twilio SMS integration not implemented');
        return true;
    }
}
