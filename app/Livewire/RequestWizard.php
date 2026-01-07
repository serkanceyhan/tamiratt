<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\ServiceRequest;
use App\Services\GhostUserService;
use App\Services\OtpService;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class RequestWizard extends Component
{
    use WithFileUploads;

    // Current step (1-5)
    public int $currentStep = 1;
    public int $totalSteps = 5;

    // Step 1: Service & Questions
    public ?int $serviceId = null;
    public array $subServiceIds = []; // Updated from single int to array
    public $groupedServices = []; // For displaying grouped options
    public array $answers = [];
    public string $description = '';

    // Step 2: Media
    public array $photos = [];
    public string $mediaToken = '';

    // Step 3: Location & Time
    public ?int $cityId = null;
    public ?int $districtId = null;
    public ?string $address = null;
    public ?string $preferredDate = null;
    public string $preferredTime = 'specific';
    public string $preferredHour = '09:00';
    public string $urgency = 'normal';

    // Step 4: Contact & OTP
    public string $phone = '';
    public string $contactName = '';
    public string $email = '';
    public string $otpCode = '';
    public bool $otpSent = false;
    public bool $phoneVerified = false;
    public int $resendCountdown = 0;
    public ?string $otpError = null;

    // State
    public bool $isSubmitting = false;
    public ?ServiceRequest $createdRequest = null;

    // Preloaded service (from SEO page)
    public ?Service $preloadedService = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(?int $serviceId = null, ?int $subServiceId = null, ?int $locationId = null)
    {
        $this->mediaToken = Str::random(32);
        
        if ($serviceId) {
            $this->serviceId = $serviceId;
            $this->preloadedService = Service::find($serviceId);
            
            // If child service is selected (via URL), switch to parent and select the child
            if ($this->preloadedService && $this->preloadedService->parent_id) {
                $this->subServiceIds = [$this->preloadedService->id];
                $this->preloadedService = $this->preloadedService->parent;
                $this->serviceId = $this->preloadedService->id;
            }
            
            // Logic for Grouped Services (Screenshot View)
            // If top-level category (no parent), load children as groups
            if ($this->preloadedService && !$this->preloadedService->parent_id) {
                $this->groupedServices = $this->preloadedService->children()
                    ->where('is_active', true)
                    ->with(['children' => function($q) {
                        $q->where('is_active', true);
                    }])
                    ->get();
            }
        }
        
        if ($subServiceId) {
            $this->subServiceIds = [$subServiceId];
        }

        // Handle location (passed directly or from query string)
        $locationId = $locationId ?? request()->query('location');
        if ($locationId) {
            $location = Location::find($locationId);
            if ($location) {
                // Check if it's a city or district
                if ($location->parent_id) {
                    // It's a district - set both city and district
                    $this->cityId = $location->parent_id;
                    $this->districtId = $location->id;
                } else {
                    // It's a city
                    $this->cityId = $location->id;
                }
            }
        }
        
        // Restore state from session
        $this->restoreState();
    }
    
    /**
     * Save the current state of the wizard to the session.
     */
    public function saveState()
    {
        $state = [
            'currentStep' => $this->currentStep,
            'serviceId' => $this->serviceId,
            'subServiceIds' => $this->subServiceIds,
            'answers' => $this->answers,
            'description' => $this->description,
            'cityId' => $this->cityId,
            'districtId' => $this->districtId,
            'address' => $this->address,
            'preferredDate' => $this->preferredDate,
            'preferredTime' => $this->preferredTime,
            'preferredHour' => $this->preferredHour,
            'urgency' => $this->urgency,
            'phone' => $this->phone,
            'contactName' => $this->contactName,
            'email' => $this->email,
            'otpSent' => $this->otpSent,
            'phoneVerified' => $this->phoneVerified,
             // Note: Photos cannot be persisted due to temporary file limits
        ];
        
        session()->put('wizard_state', $state);
    }
    
    /**
     * Restore the wizard state from the session.
     */
    public function restoreState()
    {
        if (session()->has('wizard_state')) {
            $state = session()->get('wizard_state');
            
            // Only restore if we are on the same service context (to avoid confusion)
            // Or if no service was initially loaded via URL
            if (!$this->serviceId || (isset($state['serviceId']) && $state['serviceId'] == $this->serviceId)) {
                $this->currentStep = $state['currentStep'] ?? 1;
                $this->serviceId = $state['serviceId'] ?? $this->serviceId;
                $this->subServiceIds = $state['subServiceIds'] ?? [];
                $this->answers = $state['answers'] ?? [];
                $this->description = $state['description'] ?? '';
                $this->cityId = $state['cityId'] ?? null;
                $this->districtId = $state['districtId'] ?? null;
                $this->address = $state['address'] ?? null;
                $this->preferredDate = $state['preferredDate'] ?? null;
                $this->preferredTime = $state['preferredTime'] ?? 'specific';
                $this->preferredHour = $state['preferredHour'] ?? '09:00';
                $this->urgency = $state['urgency'] ?? 'normal';
                $this->phone = $state['phone'] ?? '';
                $this->contactName = $state['contactName'] ?? '';
                $this->email = $state['email'] ?? '';
                $this->otpSent = $state['otpSent'] ?? false;
                $this->phoneVerified = $state['phoneVerified'] ?? false;
            }
        }
    }
    
    public function updated($propertyName)
    {
        $this->saveState(); // Auto-save on any update
    }

    public function nextStep()
    {
        try {
            $this->validateCurrentStep();
            
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
                $this->saveState();
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Wizard nextStep error', [
                'step' => $this->currentStep,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Re-throw validation exceptions so they show in UI
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            
            // Flash generic error for other exceptions
            session()->flash('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->saveState();
        }
    }

    public function goToStep(int $step)
    {
        // Only allow going to completed steps or the next available step
        if ($step <= $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    protected function validateCurrentStep(): void
    {
        match ($this->currentStep) {
            1 => $this->validateStep1(),
            2 => $this->validateStep2(),
            3 => $this->validateStep3(),
            4 => $this->validateStep4(), // Contact info (name/email)
            5 => $this->validateStep5(), // Phone verification
            default => null,
        };
    }

    // ==================== STEP 1: SERVICE & QUESTIONS ====================

    public function getParentServicesProperty()
    {
        return Service::whereNull('parent_id')
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getSubServicesProperty()
    {
        if (!$this->serviceId) {
            return collect();
        }

        return Service::where('parent_id', $this->serviceId)
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getQuestionsProperty()
    {
        $questions = collect();

        // Get parent service questions
        if ($this->serviceId) {
            $questions = $questions->merge(
                ServiceQuestion::where('service_id', $this->serviceId)
                    ->active()
                    ->root()
                    ->ordered()
                    ->get()
            );
        }

        // Get sub-service questions (all selected services)
        if (!empty($this->subServiceIds)) {
            $questions = $questions->merge(
                ServiceQuestion::whereIn('service_id', $this->subServiceIds)
                    ->active()
                    ->root()
                    ->ordered()
                    ->get()
            );
        }

        return $questions;
    }

    public function getSelectedServiceProperty()
    {
        // If preloaded service is available, return it (Parent Category)
        if ($this->preloadedService) {
            return $this->preloadedService;
        }

        return Service::find($this->serviceId);
    }

    public function updatedServiceId($value)
    {
        $this->subServiceIds = [];
        $this->answers = [];
    }

    protected function validateStep1(): void
    {
        $this->validate([
            'serviceId' => 'required|exists:services,id',
            'subServiceIds' => 'required|array|min:1', // Ensure at least one sub-service is selected
            'description' => 'required|min:20',
        ], [
            'serviceId.required' => 'Lütfen bir hizmet kategorisi seçin.',
            'subServiceIds.required' => 'Lütfen en az bir hizmet seçin.',
            'subServiceIds.min' => 'Lütfen en az bir hizmet seçin.',
            'description.required' => 'Lütfen ihtiyacınızı açıklayın.',
            'description.min' => 'Açıklama en az 20 karakter olmalıdır.',
        ]);

        // Validate required questions (skipped as per user request to remove questions UI, but keeping logic just in case)
        // $requiredQuestions = $this->questions->where('is_required', true);
        $requiredQuestions = $this->questions->where('is_required', true);
        
        foreach ($requiredQuestions as $question) {
            if (!isset($this->answers[$question->id]) || empty($this->answers[$question->id])) {
                $this->addError('answers.' . $question->id, 'Bu soru zorunludur.');
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'answers.' . $question->id => 'Bu soru zorunludur.',
                ]);
            }
        }
    }

    // ==================== STEP 2: MEDIA UPLOAD ====================

    public function updatedPhotos()
    {
        $this->validate([
            'photos.*' => 'image|max:10240', // 10MB max per file
        ], [
            'photos.*.image' => 'Sadece resim dosyaları yükleyebilirsiniz.',
            'photos.*.max' => 'Dosya boyutu en fazla 10MB olabilir.',
        ]);
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
        }
    }

    protected function validateStep2(): void
    {
        // Step 2: Photos (Optional)
        // No validation needed here as photos are optional and validated on upload
    }

    // ==================== STEP 3: LOCATION & TIME ====================

    public function getCitiesProperty()
    {
        return Location::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getDistrictsProperty()
    {
        if (!$this->cityId) {
            return collect();
        }

        return Location::where('parent_id', $this->cityId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function updatedCityId($value)
    {
        $this->districtId = null;
    }

    protected function validateStep3(): void
    {
        $this->validate([
            'preferredTime' => 'required|in:specific,two_months,six_months,just_looking',
        ], [
            'preferredTime.required' => 'Lütfen bir zaman seçeneği seçin.',
        ]);
        
        // If specific time is selected, validate date
        if ($this->preferredTime === 'specific') {
            $this->validate([
                'preferredDate' => 'required|date|after_or_equal:today',
            ], [
                'preferredDate.required' => 'Lütfen bir tarih seçin.',
            ]);
        }
    }

    // ==================== STEP 4: CONTACT & OTP ====================

    public function sendOtp()
    {
        \Log::info('sendOtp method called', ['phone' => $this->phone]);
        
        try {
            $this->validate([
                'phone' => 'required|regex:/^5[0-9]{9}$/',
            ], [
                'phone.required' => 'Telefon numarası zorunludur.',
                'phone.regex' => 'Geçerli bir telefon numarası girin (5XX XXX XX XX).',
            ]);

            \Log::info('Phone validation passed', ['phone' => $this->phone]);

            $otpService = app(OtpService::class);
            $result = $otpService->send($this->phone);
            
            \Log::info('OTP service result', $result);

            if ($result['success']) {
                $this->otpSent = true;
                $this->otpError = null;
                $this->resendCountdown = $result['seconds_until_resend'] ?? 60;
                
                // Debug: Show OTP in development
                if (app()->environment('local')) {
                    session()->flash('debug_message', 'Doğrulama kodu: ' . session('debug_otp_code'));
                }
            } else {
                $this->otpError = $result['message'];
                if (isset($result['seconds_until_resend'])) {
                    $this->resendCountdown = $result['seconds_until_resend'];
                }
            }
        } catch (\Exception $e) {
            \Log::error('sendOtp exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->otpError = 'Bir hata oluştu: ' . $e->getMessage();
        }
    }

    public function verifyOtp()
    {
        \Log::info('verifyOtp method called', ['phone' => $this->phone, 'otpCode' => $this->otpCode]);
        
        try {
            $this->validate([
                'otpCode' => 'required|digits:6',
            ], [
                'otpCode.required' => 'Doğrulama kodunu girin.',
                'otpCode.digits' => 'Doğrulama kodu 6 haneli olmalıdır.',
            ]);

            $otpService = app(OtpService::class);
            $result = $otpService->verify($this->phone, $this->otpCode);
            
            \Log::info('OTP verify result', $result);

            if ($result['success']) {
                $this->phoneVerified = true;
                $this->otpError = null;
                $this->saveState(); // Save state after successful verification
                \Log::info('Phone verified successfully');
            } else {
                $this->otpError = $result['message'];
            }
        } catch (\Exception $e) {
            \Log::error('verifyOtp exception', ['error' => $e->getMessage()]);
            $this->otpError = 'Bir hata oluştu: ' . $e->getMessage();
        }
    }

    public function changePhone()
    {
        $this->otpSent = false;
        $this->phoneVerified = false;
        $this->otpCode = '';
        $this->otpError = null;
        $this->saveState();
    }

    protected function validateStep4(): void
    {
        // Step 4: Contact info & Location
        $this->validate([
            'cityId' => 'required|exists:locations,id',
            'contactName' => 'required|min:2|max:255',
            'email' => 'nullable|email|max:255',
        ], [
            'cityId.required' => 'Lütfen bir şehir seçin.',
            'contactName.required' => 'İsim soyisim zorunludur.',
            'contactName.min' => 'İsim en az 2 karakter olmalıdır.',
        ]);
    }

    protected function validateStep5(): void
    {
        // Step 5: Phone verification
        $this->validate([
            'phone' => 'required|regex:/^5[0-9]{9}$/',
        ], [
            'phone.required' => 'Telefon numarası zorunludur.',
            'phone.regex' => 'Geçerli bir telefon numarası girin (5XX XXX XX XX).',
        ]);

        if (!$this->phoneVerified) {
            $this->addError('phone', 'Telefon numaranızı doğrulamanız gerekiyor.');
            throw \Illuminate\Validation\ValidationException::withMessages([
                'phone' => 'Telefon numaranızı doğrulamanız gerekiyor.',
            ]);
        }
    }

    // ==================== SUBMIT ====================

    public function submit()
    {
        \Log::info('Submit method called', ['step' => $this->currentStep, 'phoneVerified' => $this->phoneVerified]);
        
        $this->isSubmitting = true;

        try {
            // Validate all steps
            \Log::info('Validating steps...');
            $this->validateStep1();
            $this->validateStep2();
            $this->validateStep3();
            $this->validateStep4();
            $this->validateStep5();

            // Get or create ghost user
            $ghostUserService = app(GhostUserService::class);
            $user = $ghostUserService->findOrCreateByPhone(
                $this->phone,
                $this->contactName ?: null,
                $this->email ?: null
            );

            // Update phone verified_at
            if (!$user->phone_verified_at) {
                $user->update(['phone_verified_at' => now()]);
            }

            // Create service request
            $serviceRequest = ServiceRequest::create([
                'user_id' => $user->id,
                'service_id' => $this->serviceId, // Main Category ID
                'sub_service_id' => null, // Deprecated, using pivot table
                'answers' => $this->answers,
                'location_id' => $this->districtId ?? $this->cityId,
                'address' => $this->address,
                'preferred_date' => $this->preferredDate,
                'preferred_time' => $this->preferredTime,
                'urgency' => $this->urgency,
                'phone' => app(GhostUserService::class)->normalizePhone($this->phone),
                'phone_verified_at' => now(),
                'contact_name' => $this->contactName,
                'email' => $this->email,
                'status' => ServiceRequest::STATUS_OPEN,
                'media_token' => $this->mediaToken,
                'lead_price' => $this->selectedService?->lead_price ?? 50.00,
                'expires_at' => now()->addDays(30),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'source' => 'wizard',
            ]);

            // Sync Selected Sub-Services
            if (!empty($this->subServiceIds)) {
                $serviceRequest->subServices()->attach($this->subServiceIds);
            }

            // Attach uploaded photos
            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $serviceRequest->addMedia($photo->getRealPath())
                        ->usingFileName($photo->getClientOriginalName())
                        ->toMediaCollection('request_photos');
                }
            }

            $this->createdRequest = $serviceRequest;
            
            // Redirect to success page
            return redirect()->route('service-request.success', ['id' => $serviceRequest->id]);

        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Bir hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    // ==================== RENDER ====================

    public function render()
    {
        return view('livewire.request-wizard');
    }
}
