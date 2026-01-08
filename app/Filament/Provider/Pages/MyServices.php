<?php

namespace App\Filament\Provider\Pages;

use App\Models\Provider;
use App\Models\Service;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MyServices extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Hizmetlerim';

    protected static ?string $title = 'Hizmetlerim';

    protected static ?string $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.provider.pages.my-services';

    public ?array $selectedServices = [];

    public function mount(): void
    {
        $provider = $this->getProvider();
        $this->selectedServices = $provider?->service_categories ?? [];
    }

    public function getProvider(): ?Provider
    {
        $user = Auth::user();
        return $user ? Provider::where('user_id', $user->id)->first() : null;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                CheckboxList::make('selectedServices')
                    ->label('Sunduğum Hizmetler')
                    ->options(Service::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->columns(2)
                    ->gridDirection('row')
                    ->descriptions(
                        Service::where('is_active', true)
                            ->get()
                            ->mapWithKeys(fn ($s) => [$s->id => $s->description ?? 'Bu hizmeti sunabiliyorsanız seçin.'])
                            ->toArray()
                    )
                    ->bulkToggleable()
                    ->helperText('En az bir hizmet seçmelisiniz. Seçtiğiniz hizmetlere uygun iş fırsatları size gösterilecek.'),
            ]);
    }

    public function save(): void
    {
        if (empty($this->selectedServices)) {
            Notification::make()
                ->title('En az bir hizmet seçmelisiniz')
                ->danger()
                ->send();
            return;
        }

        $provider = $this->getProvider();
        
        if (!$provider) {
            Notification::make()
                ->title('Provider bulunamadı')
                ->danger()
                ->send();
            return;
        }

        $provider->update([
            'service_categories' => $this->selectedServices,
        ]);

        Notification::make()
            ->title('Hizmetleriniz güncellendi')
            ->body(count($this->selectedServices) . ' hizmet seçildi.')
            ->success()
            ->send();
    }

    public function getSelectedServicesCountProperty(): int
    {
        return count($this->selectedServices);
    }

    public function getAvailableServicesProperty()
    {
        return Service::where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
