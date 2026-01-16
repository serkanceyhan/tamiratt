<?php

namespace App\Filament\Customer\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Profilim extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    
    protected static ?string $navigationLabel = 'Profilim';
    
    protected static ?string $title = 'Profilim';
    
    protected static ?string $navigationGroup = 'Ayarlar';
    
    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.customer.pages.profilim';

    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $user = Auth::user();
        
        $this->profileForm->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'passwordForm',
        ];
    }

    public function profileForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kişisel Bilgiler')
                    ->description('Hesap bilgilerinizi güncelleyin.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'email', ignorable: Auth::user()),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),
            ])
            ->statePath('profileData');
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Şifre Değiştir')
                    ->description('Güvenliğiniz için güçlü bir şifre kullanın.')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Mevcut Şifre')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        Forms\Components\TextInput::make('password')
                            ->label('Yeni Şifre')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->same('password_confirmation'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Yeni Şifre (Tekrar)')
                            ->password()
                            ->required(),
                    ])
                    ->columns(1),
            ])
            ->statePath('passwordData');
    }

    public function updateProfile(): void
    {
        $data = $this->profileForm->getState();

        Auth::user()->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        Notification::make()
            ->title('Profil güncellendi')
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();

        Auth::user()->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->passwordForm->fill();

        Notification::make()
            ->title('Şifre değiştirildi')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
