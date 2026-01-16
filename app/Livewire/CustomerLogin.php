<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\GhostUserService;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CustomerLogin extends Component
{
    public string $phone = '';
    public string $name = '';
    public string $otpCode = '';
    
    public bool $otpSent = false;
    public bool $isNewUser = false;
    public int $resendCountdown = 0;
    public ?string $error = null;
    public ?string $success = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function sendOtp()
    {
        $this->validate([
            'phone' => 'required|regex:/^5[0-9]{9}$/',
        ], [
            'phone.required' => 'Telefon numarası zorunludur.',
            'phone.regex' => 'Geçerli bir telefon numarası girin (5XX XXX XX XX).',
        ]);

        $this->error = null;

        $otpService = app(OtpService::class);
        $result = $otpService->send($this->phone);

        if ($result['success']) {
            $this->otpSent = true;
            $this->resendCountdown = $result['seconds_until_resend'] ?? 60;
            
            // Check if user exists
            $ghostUserService = app(GhostUserService::class);
            $normalizedPhone = $ghostUserService->normalizePhone($this->phone);
            $existingUser = User::where('phone', $normalizedPhone)->first();
            
            $this->isNewUser = !$existingUser || $existingUser->is_ghost;
            
            // Debug: Show OTP in development
            if (app()->environment('local')) {
                session()->flash('debug_message', 'Doğrulama kodu: ' . session('debug_otp_code'));
            }
        } else {
            $this->error = $result['message'];
            if (isset($result['seconds_until_resend'])) {
                $this->resendCountdown = $result['seconds_until_resend'];
            }
        }
    }

    public function verifyOtp()
    {
        $rules = [
            'otpCode' => 'required|digits:6',
        ];

        if ($this->isNewUser) {
            $rules['name'] = 'required|min:2|max:255';
        }

        $this->validate($rules, [
            'otpCode.required' => 'Doğrulama kodunu girin.',
            'otpCode.digits' => 'Doğrulama kodu 6 haneli olmalıdır.',
            'name.required' => 'Ad soyad zorunludur.',
            'name.min' => 'Ad soyad en az 2 karakter olmalıdır.',
        ]);

        $this->error = null;

        $otpService = app(OtpService::class);
        $result = $otpService->verify($this->phone, $this->otpCode);

        if ($result['success']) {
            // Get or create user
            $ghostUserService = app(GhostUserService::class);
            $user = $ghostUserService->findOrCreateByPhone(
                $this->phone,
                $this->isNewUser ? $this->name : null
            );

            // Update phone verified
            if (!$user->phone_verified_at) {
                $user->update(['phone_verified_at' => now()]);
            }

            // Convert ghost user if name provided
            if ($this->isNewUser && $user->is_ghost && $this->name) {
                $user->update([
                    'name' => $this->name,
                    'is_ghost' => false,
                ]);
            }

            // Login the user
            Auth::login($user, true); // Remember me

            $this->success = 'Giriş başarılı! Yönlendiriliyorsunuz...';

            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard'));
        } else {
            $this->error = $result['message'];
        }
    }

    public function changePhone()
    {
        $this->otpSent = false;
        $this->otpCode = '';
        $this->error = null;
        $this->isNewUser = false;
    }

    public function render()
    {
        return view('livewire.customer-login');
    }
}
