<?php

namespace App\Livewire\Customer;

use App\Models\ServiceRequest;
use Livewire\Component;

class LatestRequestCard extends Component
{
    public ServiceRequest $request;
    public bool $showCancelModal = false;
    public bool $showEditModal = false;
    public $editDescription = '';
    public $editDate = null;

    public function mount(ServiceRequest $request)
    {
        $this->request = $request;
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
            $this->dispatch('notify', message: 'Bu talep iptal edilemez.', type: 'error');
            return;
        }

        $this->request->update([
            'status' => ServiceRequest::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $this->cancelReason,
        ]);

        $this->closeCancelModal();
        $this->dispatch('notify', message: 'Talebiniz iptal edildi.', type: 'success');
        
        // Refresh the page to reflect changes
        return redirect()->route('dashboard');
    }

    public function markAsCompleted()
    {
        if ($this->request->status !== 'locked') {
            $this->dispatch('notify', message: 'Bu talep tamamlandı olarak işaretlenemez.', type: 'error');
            return;
        }

        $this->request->update([
            'status' => ServiceRequest::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $this->dispatch('notify', message: 'Talebiniz tamamlandı olarak işaretlendi.', type: 'success');
        return redirect()->route('dashboard');
    }

    public function openEditModal()
    {
        if (!in_array($this->request->status, ['open', 'draft', 'pending_verification'])) {
             $this->dispatch('notify', message: 'Bu talep artık düzenlenemez.', type: 'error');
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
        $this->dispatch('notify', message: 'Talebiniz güncellendi.', type: 'success');
        
        // Refresh to show updated data
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.customer.latest-request-card');
    }
}
