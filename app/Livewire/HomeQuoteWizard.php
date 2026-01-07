<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\Service;
use Livewire\Component;

class HomeQuoteWizard extends Component
{
    public int $currentStep = 1;
    public int $totalSteps = 3;

    // Step 1: Service Selection
    public ?int $serviceId = null;

    // Step 2: Sub-Service Selection
    public ?int $subServiceId = null;

    // Step 3: Location Selection
    public ?int $cityId = null;
    public ?int $districtId = null;

    protected $rules = [
        'serviceId' => 'required|exists:services,id',
        'subServiceId' => 'nullable|exists:services,id',
        'cityId' => 'required|exists:locations,id',
        'districtId' => 'nullable|exists:locations,id',
    ];

    protected $messages = [
        'serviceId.required' => 'Lütfen bir hizmet seçin.',
        'cityId.required' => 'Lütfen bir şehir seçin.',
    ];

    public function getServicesProperty()
    {
        return Service::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getSubServicesProperty()
    {
        if (!$this->serviceId) {
            return collect();
        }

        return Service::where('parent_id', $this->serviceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getCitiesProperty()
    {
        return Location::where('type', 'city')
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
            ->where('type', 'district')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function updatedServiceId($value)
    {
        $this->subServiceId = null;
    }

    public function updatedCityId($value)
    {
        $this->districtId = null;
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate(['serviceId' => 'required|exists:services,id'], [
                'serviceId.required' => 'Lütfen bir hizmet seçin.',
            ]);
        }

        if ($this->currentStep === 2) {
            // Sub-service is optional, just proceed
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit()
    {
        $this->validate([
            'serviceId' => 'required|exists:services,id',
            'cityId' => 'required|exists:locations,id',
        ]);

        // Build the redirect URL
        $service = Service::find($this->subServiceId ?? $this->serviceId);
        $location = Location::find($this->districtId ?? $this->cityId);

        if ($service && $location) {
            return redirect()->route('service-request.create.location', [
                'serviceSlug' => $service->slug,
                'locationSlug' => $location->slug ?? null,
            ]);
        } elseif ($service) {
            return redirect()->route('service-request.create', [
                'serviceSlug' => $service->slug,
            ]);
        }

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.home-quote-wizard');
    }
}
