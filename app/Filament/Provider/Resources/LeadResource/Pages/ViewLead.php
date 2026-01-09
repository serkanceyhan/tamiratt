<?php

namespace App\Filament\Provider\Resources\LeadResource\Pages;

use App\Filament\Provider\Resources\LeadResource;
use App\Models\Provider;
use App\Models\ProviderOffer;
use App\Models\QuotePurchase;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        $provider = $this->getProvider();
        $hasOffer = $provider && $this->hasExistingOffer($provider);
        $offerCost = $this->record->lead_price ?? 10;

        // Already submitted offer - show contact options
        if ($hasOffer) {
            return [
                Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn () => 'https://wa.me/90' . preg_replace('/[^0-9]/', '', $this->record->phone))
                    ->openUrlInNewTab(),
                Actions\Action::make('call')
                    ->label('Müşteriyi Ara')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->url(fn () => 'tel:+90' . $this->record->phone),
                Actions\Action::make('viewOffer')
                    ->label('Teklifimi Gör')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->url(fn () => route('filament.panel.resources.my-offers.index')),
            ];
        }

        // No offer yet - show offer form
        return [
            Actions\Action::make('reject')
                ->label('Reddet')
                ->icon('heroicon-o-trash')
                ->color('gray')
                ->outlined()
                ->requiresConfirmation()
                ->modalHeading('Bu fırsatı reddet')
                ->modalDescription('Bu iş fırsatını reddetmek istediğinizden emin misiniz?')
                ->action(function () {
                    // Just redirect back to list - user chose not to offer
                    $this->redirect(LeadResource::getUrl('index'));
                }),
            Actions\Action::make('submitOffer')
                ->label('Teklif ver (' . number_format($offerCost, 2, ',', '.') . ' TL)')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->size('lg')
                ->form([
                    Forms\Components\Section::make('Teklif ver')
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->label('Fiyat (KDV dahil)')
                                ->numeric()
                                ->required()
                                ->suffix('TL')
                                ->placeholder('Örn: 8500')
                                ->minValue(1)
                                ->helperText('Müşteriye sunacağınız toplam fiyatı girin.'),
                            Forms\Components\Textarea::make('message')
                                ->label('Mesaj')
                                ->required()
                                ->rows(5)
                                ->minLength(20)
                                ->maxLength(2000)
                                ->placeholder('Müşteriye sunacağınız toplam fiyatı girin.')
                                ->live(onBlur: true)
                                ->characterLimit(2000)
                                ->hint('Müşteriye detaylı bilgi verin'),
                        ])
                        ->columns(1),
                ])
                ->modalHeading('Teklif Ver')
                ->modalDescription('Bu teklif için bakiyenizden ' . number_format($offerCost, 2, ',', '.') . ' TL düşülecektir.')
                ->modalSubmitActionLabel('Teklif ver (' . number_format($offerCost, 2, ',', '.') . ' TL)')
                ->action(function (array $data) use ($provider, $offerCost) {
                    if (!$provider) {
                        Notification::make()
                            ->title('Hata')
                            ->body('Provider profili bulunamadı.')
                            ->danger()
                            ->send();
                        return;
                    }

                    if (!$provider->hasBalance($offerCost)) {
                        Notification::make()
                            ->title('Yetersiz Bakiye')
                            ->body('Bakiyeniz yetersiz. Lütfen bakiye yükleyin.')
                            ->danger()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('addBalance')
                                    ->label('Bakiye Yükle')
                                    ->url(route('filament.panel.pages.wallet'))
                                    ->button(),
                            ])
                            ->send();
                        return;
                    }

                    try {
                        DB::transaction(function () use ($provider, $offerCost, $data) {
                            // Deduct balance
                            $provider->deductBalance(
                                $offerCost,
                                'Teklif gönderildi: ' . $this->record->service->name,
                                $this->record->id
                            );

                            // Create offer record
                            ProviderOffer::create([
                                'service_request_id' => $this->record->id,
                                'provider_id' => $provider->id,
                                'price' => $data['price'],
                                'description' => $data['message'],
                                'status' => 'pending',
                            ]);

                            // Also create purchase record for tracking
                            QuotePurchase::create([
                                'provider_id' => $provider->id,
                                'service_request_id' => $this->record->id,
                                'amount_paid' => $offerCost,
                                'purchased_at' => now(),
                            ]);
                        });

                        Notification::make()
                            ->title('Teklif Gönderildi! 🎉')
                            ->body('Teklifiniz müşteriye iletildi. Müşteri iletişim bilgilerine artık erişebilirsiniz.')
                            ->success()
                            ->send();

                        $this->redirect(request()->url());
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Hata')
                            ->body('Bir hata oluştu: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getProvider(): ?Provider
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        return Provider::where('user_id', $user->id)->first();
    }

    protected function hasExistingOffer(Provider $provider): bool
    {
        return ProviderOffer::where('provider_id', $provider->id)
            ->where('service_request_id', $this->record->id)
            ->exists();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $provider = $this->getProvider();
        $hasOffer = $provider && $this->hasExistingOffer($provider);

        if (!$hasOffer) {
            // Mask sensitive data until offer is submitted
            $data['contact_name'] = '🔒 Teklif verin';
            $data['phone'] = '🔒 Teklif verin';
            $data['email'] = '🔒 Teklif verin';
        }

        return $data;
    }
}

