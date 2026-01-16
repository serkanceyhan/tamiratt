<?php

namespace App\Filament\Customer\Pages;

use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class SupportCenter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    
    protected static ?string $navigationLabel = 'Yardım Merkezi';
    
    protected static ?string $title = 'Yardım ve Destek';

    protected static string $view = 'filament.customer.pages.support-center';
    
    public ?array $data = [];
    
    // Filter properties
    public string $timeFilter = '1_month';
    public int $offerLimit = 6;
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function updatedTimeFilter(): void
    {
        $this->offerLimit = 6;
    }
    
    public function loadMoreOffers(): void
    {
        $this->offerLimit += 6;
    }
    
    public function getOffersProperty()
    {
        $userId = auth()->id();
        
        $query = \App\Models\ProviderOffer::whereHas('serviceRequest', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['provider', 'serviceRequest'])
        ->latest();
        
        // Apply Time Filter
        match($this->timeFilter) {
            '1_month' => $query->where('created_at', '>=', now()->subMonth()),
            '3_months' => $query->where('created_at', '>=', now()->subMonths(3)),
            '1_year' => $query->where('created_at', '>=', now()->subYear()),
            default => null,
        };
        
        return $query->take($this->offerLimit)->get()->map(function ($offer) {
            $offer->status_label = match($offer->status) {
                'pending' => 'Bekliyor',
                'accepted' => 'Kabul Edildi',
                'rejected' => 'Reddedildi',
                'completed' => 'Tamamlandı', // Assuming there is a completed status
                default => ucfirst($offer->status),
            };
            
            $offer->status_color = match($offer->status) {
                'pending' => 'text-yellow-600 bg-yellow-50 ring-yellow-500/10',
                'accepted' => 'text-green-600 bg-green-50 ring-green-500/10',
                'rejected' => 'text-red-600 bg-red-50 ring-red-500/10',
                default => 'text-gray-600 bg-gray-50 ring-gray-500/10',
            };
            
            return $offer;
        });
    }

    public function getFaqsProperty()
    {
        return \App\Models\Faq::query()
            ->where('is_active', true)
            ->where('category', $this->data['category'] ?? 'general')
            ->ordered()
            ->get();
    }
    
    public function getHasMoreOffersProperty(): bool
    {
        $userId = auth()->id();
        
        $query = \App\Models\ProviderOffer::whereHas('serviceRequest', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
        
        match($this->timeFilter) {
            '1_month' => $query->where('created_at', '>=', now()->subMonth()),
            '3_months' => $query->where('created_at', '>=', now()->subMonths(3)),
            '1_year' => $query->where('created_at', '>=', now()->subYear()),
            default => null,
        };
        
        return $query->count() > $this->offerLimit;
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('category'),
                
                Forms\Components\Group::make([
                    Forms\Components\Hidden::make('provider_offer_id'),
                    
                    Forms\Components\TextInput::make('subject')
                        ->label('Konu Başlığı')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\Textarea::make('description')
                        ->label('Detaylı Açıklama')
                        ->required()
                        ->rows(5),
                    
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Fotoğraf/Belge Ekle')
                        ->multiple()
                        ->maxFiles(5)
                        ->acceptedFileTypes(['image/*', 'application/pdf'])
                        ->maxSize(5120),
                ])
                ->visible(fn (Forms\Get $get) => filled($get('category'))),
            ])
            ->statePath('data');
    }
    
    public function submit(): void
    {
        $data = $this->form->getState();
        
        SupportTicket::create([
            'user_id' => auth()->id(),
            'user_type' => 'customer',
            'category' => $data['category'],
            'provider_offer_id' => $data['provider_offer_id'] ?? null,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'attachments' => $data['attachments'] ?? null,
            'status' => 'pending',
            'priority' => 'medium',
        ]);
        
        Notification::make()
            ->title('Destek talebiniz oluşturuldu')
            ->success()
            ->body('24 saat içinde size dönüş yapacağız.')
            ->send();
        
        $this->form->fill();
    }
}
