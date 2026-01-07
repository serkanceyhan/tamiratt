<?php

namespace App\Filament\Resources\ProviderResource\Pages;

use App\Filament\Resources\ProviderResource;
use App\Models\Provider;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewProvider extends ViewRecord
{
    protected static string $resource = ProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Onayla')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (Provider $record): bool => $record->verification_status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Başvuruyu Onayla')
                ->modalDescription('Bu hizmet veren onaylanacak ve aktivasyon linki gönderilecek.')
                ->action(function (Provider $record) {
                    $record->update([
                        'verification_status' => 'approved',
                        'activation_token' => Str::random(64),
                    ]);
                    
                    // TODO: Send activation email/SMS
                    
                    Notification::make()
                        ->title('Başvuru onaylandı')
                        ->success()
                        ->send();
                    
                    $this->refreshFormData(['verification_status']);
                }),
                
            Actions\Action::make('reject')
                ->label('Reddet')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (Provider $record): bool => $record->verification_status === 'pending')
                ->form([
                    Forms\Components\Textarea::make('reason')
                        ->label('Red Sebebi')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (Provider $record, array $data) {
                    $record->update([
                        'verification_status' => 'rejected',
                        'verification_notes' => $data['reason'],
                    ]);
                    
                    Notification::make()
                        ->title('Başvuru reddedildi')
                        ->warning()
                        ->send();

                    $this->refreshFormData(['verification_status', 'verification_notes']);
                }),
                
            Actions\Action::make('deactivate')
                ->label('Pasife Al')
                ->icon('heroicon-o-pause-circle')
                ->color('warning')
                ->visible(fn (Provider $record): bool => $record->is_active)
                ->requiresConfirmation()
                ->modalHeading('Hizmet Vereni Pasife Al')
                ->modalDescription('Bu hizmet veren pasife alınacak ve sisteme giriş yapamayacak. Onaylamak istiyor musunuz?')
                ->action(function (Provider $record) {
                    $record->update(['is_active' => false]);
                    Notification::make()
                        ->title('Hizmet veren pasife alındı')
                        ->success()
                        ->send();
                    $this->refreshFormData(['is_active']);
                }),

            Actions\Action::make('activate')
                ->label('Aktife Al')
                ->icon('heroicon-o-play-circle')
                ->color('success')
                ->visible(fn (Provider $record): bool => !$record->is_active && $record->isApproved())
                ->requiresConfirmation()
                ->modalHeading('Hizmet Vereni Aktife Al')
                ->modalDescription('Bu hizmet veren tekrar aktif edilecek. Onaylamak istiyor musunuz?')
                ->action(function (Provider $record) {
                    $record->update(['is_active' => true]);
                    Notification::make()
                        ->title('Hizmet veren aktif edildi')
                        ->success()
                        ->send();
                    $this->refreshFormData(['is_active']);
                }),

            Actions\EditAction::make(),
        ];
    }
}
