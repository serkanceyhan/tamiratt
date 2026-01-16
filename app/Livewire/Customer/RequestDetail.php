<?php

namespace App\Livewire\Customer;

use App\Models\ServiceRequest;
use App\Models\ProviderOffer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RequestDetail extends Component
{
    public ServiceRequest $request;
    public bool $showCancelModal = false;
    public bool $showEditModal = false;
    public $editDescription = '';
    public $editDate = null;

    public function mount(int $id)
    {
        $this->request = ServiceRequest::where('user_id', Auth::id())
            ->with(['service', 'subServices', 'location', 'offers.provider.user', 'media'])
            ->findOrFail($id);
    }

    public function getOffersProperty()
    {
        return $this->request->offers()
            ->with(['provider.user'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getSelectedOfferProperty()
    {
        return $this->request->offers()
            ->where('status', 'accepted')
            ->with(['provider.user'])
            ->first();
    }

    public function getPhotosProperty()
    {
        return $this->request->getMedia('request_photos');
    }

    public function openCancelModal()
    {
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->cancelReason = '';
    }

    public function cancelRequest()
    {
        if (!in_array($this->request->status, ['open', 'locked'])) {
            session()->flash('error', 'Bu talep iptal edilemez.');
            return;
        }

        $this->request->update([
            'status' => ServiceRequest::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $this->cancelReason,
        ]);

        $this->closeCancelModal();
        session()->flash('success', 'Talebiniz iptal edildi.');
        
        return redirect()->route('dashboard');
    }

    public function markAsCompleted()
    {
        if ($this->request->status !== 'locked') {
            session()->flash('error', 'Bu talep tamamlandı olarak işaretlenemez.');
            return;
        }

        $this->request->update([
            'status' => ServiceRequest::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        session()->flash('success', 'Talebiniz tamamlandı olarak işaretlendi.');
    }

    public function openEditModal()
    {
        if (!in_array($this->request->status, ['open', 'draft', 'pending_verification'])) {
             session()->flash('error', 'Bu talep artık düzenlenemez.');
             return;
        }
        
        $this->editDescription = $this->request->description;
        $this->editDate = $this->request->preferred_date ? $this->request->preferred_date->format('Y-m-d') : null;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function updateRequest()
    {
        $this->validate([
            'editDescription' => 'required|string|min:10',
            'editDate' => 'nullable|date|after:yesterday',
        ], [
            'editDescription.required' => 'Açıklama alanı zorunludur.',
            'editDescription.min' => 'Açıklama en az 10 karakter olmalıdır.',
            'editDate.after' => 'Geçmiş bir tarih seçemezsiniz.',
        ]);

        $this->request->update([
            'description' => $this->editDescription,
            'preferred_date' => $this->editDate,
        ]);

        $this->closeEditModal();
        session()->flash('success', 'Talebiniz güncellendi.');
    }

    public function render()
    {
        return view('livewire.customer.request-detail');
    }
}
