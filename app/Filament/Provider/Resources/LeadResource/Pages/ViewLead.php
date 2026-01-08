<?php

namespace App\Filament\Provider\Resources\LeadResource\Pages;

use App\Filament\Provider\Resources\LeadResource;
use App\Models\Provider;
use App\Models\QuotePurchase;
use Filament\Actions;
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
        $isUnlocked = $provider && $provider->hasPurchasedQuote($this->record->id);
        $unlockCost = 10; // TODO: Make this configurable per service

        if ($isUnlocked) {
            return [
                Actions\Action::make('whatsapp')
                    ->label('WhatsApp ile İletişim')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn () => 'https://wa.me/90' . preg_replace('/[^0-9]/', '', $this->record->phone))
                    ->openUrlInNewTab(),
                Actions\Action::make('call')
                    ->label('Telefon Et')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->url(fn () => 'tel:+90' . $this->record->phone),
            ];
        }

        return [
            Actions\Action::make('unlock')
                ->label('Kilidi Aç (' . $unlockCost . ' ₺)')
                ->icon('heroicon-o-lock-open')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('İletişim Bilgilerini Aç')
                ->modalDescription('Bu işlem bakiyenizden ' . $unlockCost . ' ₺ düşecektir. Devam etmek istiyor musunuz?')
                ->modalSubmitActionLabel('Kilidi Aç')
                ->action(function () use ($provider, $unlockCost) {
                    if (!$provider) {
                        Notification::make()
                            ->title('Hata')
                            ->body('Provider profili bulunamadı.')
                            ->danger()
                            ->send();
                        return;
                    }

                    if (!$provider->hasBalance($unlockCost)) {
                        Notification::make()
                            ->title('Yetersiz Bakiye')
                            ->body('Bakiyeniz yetersiz. Lütfen bakiye yükleyin.')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        DB::transaction(function () use ($provider, $unlockCost) {
                            // Deduct balance
                            $provider->deductBalance(
                                $unlockCost,
                                'İş fırsatı kilidi açıldı: ' . $this->record->service->name,
                                $this->record->id
                            );

                            // Create purchase record
                            QuotePurchase::create([
                                'provider_id' => $provider->id,
                                'quote_id' => $this->record->id,
                                'amount' => $unlockCost,
                            ]);
                        });

                        Notification::make()
                            ->title('Kilit Açıldı!')
                            ->body('Müşteri bilgileri artık görüntülenebilir.')
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $provider = $this->getProvider();
        $isUnlocked = $provider && $provider->hasPurchasedQuote($this->record->id);

        if (!$isUnlocked) {
            // Mask sensitive data
            $data['contact_name'] = '🔒 Kilidi Aç';
            $data['phone'] = '🔒 Kilidi Aç';
            $data['email'] = '🔒 Kilidi Aç';
        }

        return $data;
    }
}
