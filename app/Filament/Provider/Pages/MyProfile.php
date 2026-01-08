<?php

namespace App\Filament\Provider\Pages;

use App\Models\Location;
use App\Models\Provider;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profilim';

    protected static ?string $title = 'Profilim';

    protected static ?string $navigationGroup = 'Ayarlar';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.provider.pages.my-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $provider = $this->getProvider();
        
        if ($provider) {
            $this->form->fill([
                'company_name' => $provider->company_name,
                'phone' => $provider->phone,
                'email' => $provider->user?->email,
                'address' => $provider->address,
                'bio' => $provider->bio,
                'city_id' => $provider->city_id,
                'district_ids' => $provider->service_areas ?? [],
                'logo' => $provider->logo,
                'tax_number' => $provider->tax_number,
                'tax_office' => $provider->tax_office,
            ]);
        }
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
                Section::make('Temel Bilgiler')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Profil Fotoğrafı / Logo')
                            ->image()
                            ->avatar()
                            ->directory('provider-logos')
                            ->columnSpanFull(),
                        TextInput::make('company_name')
                            ->label('Firma Adı')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->disabled()
                            ->helperText('E-posta adresinizi değiştirmek için destek ile iletişime geçin.'),
                    ])
                    ->columns(2),

                Section::make('Konum Tercihleri')
                    ->schema([
                        Select::make('city_id')
                            ->label('İl')
                            ->options(
                                Location::where('type', 'city')
                                    ->where('is_active', true)
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('district_ids', [])),
                        Select::make('district_ids')
                            ->label('İlçeler')
                            ->multiple()
                            ->options(function (callable $get) {
                                $cityId = $get('city_id');
                                if (!$cityId) return [];
                                return Location::where('type', 'district')
                                    ->where('parent_id', $cityId)
                                    ->where('is_active', true)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->helperText('Hizmet verdiğiniz ilçeleri seçin. Seçtiğiniz ilçelerden iş fırsatları size gösterilecek.'),
                    ])
                    ->columns(2),

                Section::make('Firma Detayları')
                    ->schema([
                        Textarea::make('bio')
                            ->label('Hakkımızda')
                            ->rows(4)
                            ->placeholder('Firmanız hakkında kısa bir açıklama yazın...')
                            ->columnSpanFull(),
                        TextInput::make('address')
                            ->label('Adres')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),

                Section::make('Fatura Bilgileri')
                    ->schema([
                        TextInput::make('tax_number')
                            ->label('Vergi Numarası')
                            ->maxLength(20),
                        TextInput::make('tax_office')
                            ->label('Vergi Dairesi')
                            ->maxLength(100),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $provider = $this->getProvider();
        
        if (!$provider) {
            Notification::make()
                ->title('Provider bulunamadı')
                ->danger()
                ->send();
            return;
        }

        $provider->update([
            'company_name' => $data['company_name'],
            'phone' => $data['phone'],
            'address' => $data['address'] ?? null,
            'bio' => $data['bio'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'service_areas' => $data['district_ids'] ?? [],
            'logo' => $data['logo'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'tax_office' => $data['tax_office'] ?? null,
        ]);

        Notification::make()
            ->title('Profil güncellendi')
            ->success()
            ->send();
    }
}
