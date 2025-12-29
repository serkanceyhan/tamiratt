<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static ?string $title = 'Genel Ayarlar';
    protected static string $view = 'filament.pages.manage-settings';
    protected static ?string $navigationGroup = 'Ayarlar';

    // Form data
    public ?array $data = [];

    public function mount(): void
    {
        // Load settings from database
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Genel Bilgiler')
                    ->description('Site genel ayarları')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Adı')
                            ->required(),
                        Toggle::make('site_active')
                            ->label('Site Yayında mı?')
                            ->helperText('Kapalı olduğunda bakım modu görünür'),
                    ])->columns(2),

                Section::make('İletişim Bilgileri')
                    ->description('Müşterilerle iletişim bilgileri')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel(),
                        TextInput::make('email')
                            ->label('E-Posta')
                            ->email(),
                        TextInput::make('whatsapp')
                            ->label('WhatsApp Numarası')
                            ->tel()
                            ->placeholder('905XXXXXXXXX')
                            ->helperText('Ülke kodu ile birlikte, boşluksuz'),
                        TextInput::make('whatsapp_agent_name')
                            ->label('WhatsApp Danışman Adı')
                            ->default('Sevda')
                            ->helperText('Chat widget\'ta görünecek danışman adı'),
                        Textarea::make('whatsapp_message')
                            ->label('WhatsApp Hoş Geldin Mesajı')
                            ->rows(3)
                            ->default('Merhaba 👋 Ben, Müşteri Danışmanınız Sevda.\n\nSize nasıl yardımcı olabilirim?')
                            ->helperText('Chat widget\'ta görünecek ilk mesaj'),
                        Textarea::make('address')
                            ->label('Adres')
                            ->rows(2),
                    ])->columns(2),
                    
                Section::make('SEO Ayarları')
                    ->description('Arama motoru optimizasyonu')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Varsayılan Meta Başlık')
                            ->maxLength(60)
                            ->helperText('Google\'da görünecek başlık (maks 60 karakter)'),
                        Textarea::make('meta_description')
                            ->label('Varsayılan Meta Açıklama')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Google\'da görünecek açıklama (maks 160 karakter)'),
                    ]),
            ])
            ->statePath('data');
    }

    // Save action
    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $group = $this->getGroupForKey($key);
            Setting::set($key, $value, $group);
        }

        Notification::make()
            ->title('Ayarlar Kaydedildi')
            ->success()
            ->send();
    }

    // Helper to determine group from key
    private function getGroupForKey(string $key): string
    {
        if (str_starts_with($key, 'meta_')) {
            return 'seo';
        } elseif (in_array($key, ['phone', 'email', 'whatsapp', 'whatsapp_agent_name', 'whatsapp_message', 'address'])) {
            return 'contact';
        }
        return 'general';
    }
    
    // Header actions (Save button)
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Değişiklikleri Kaydet')
                ->action('save')
                ->color('primary'),
        ];
    }
}
