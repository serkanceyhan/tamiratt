<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Kullanıcılar';

    protected static ?string $modelLabel = 'Kullanıcı';

    protected static ?string $pluralModelLabel = 'Kullanıcılar';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kullanıcı Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('user_type')
                            ->label('Kullanıcı Tipi')
                            ->options([
                                User::TYPE_CUSTOMER => 'Müşteri',
                                User::TYPE_PROVIDER => 'Hizmet Veren',
                                User::TYPE_ADMIN => 'Yönetici',
                            ])
                            ->required()
                            ->default(User::TYPE_CUSTOMER),
                        Forms\Components\TextInput::make('password')
                            ->label('Şifre')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->helperText('Düzenlerken boş bırakırsanız şifre değişmez.'),
                        Forms\Components\Toggle::make('is_ghost')
                            ->label('Ghost Kullanıcı')
                            ->helperText('OTP ile oluşturulmuş, şifresi olmayan kullanıcı')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('user_type')
                    ->label('Tip')
                    ->colors([
                        'success' => User::TYPE_ADMIN,
                        'primary' => User::TYPE_PROVIDER,
                        'secondary' => User::TYPE_CUSTOMER,
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        User::TYPE_ADMIN => 'Yönetici',
                        User::TYPE_PROVIDER => 'Hizmet Veren',
                        User::TYPE_CUSTOMER => 'Müşteri',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('is_ghost')
                    ->label('Ghost')
                    ->boolean()
                    ->trueIcon('heroicon-o-user-minus')
                    ->falseIcon('heroicon-o-user-plus')
                    ->trueColor('warning')
                    ->falseColor('success'),
                Tables\Columns\TextColumn::make('provider.company_name')
                    ->label('Firma')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_type')
                    ->label('Kullanıcı Tipi')
                    ->options([
                        User::TYPE_CUSTOMER => 'Müşteri',
                        User::TYPE_PROVIDER => 'Hizmet Veren',
                        User::TYPE_ADMIN => 'Yönetici',
                    ]),
                Tables\Filters\TernaryFilter::make('is_ghost')
                    ->label('Ghost Kullanıcı'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resetPassword')
                    ->label('Şifre Sıfırla')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label('Yeni Şifre')
                            ->password()
                            ->required()
                            ->minLength(8),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'password' => $data['new_password'], // Hashed automatically by User model cast
                            'is_ghost' => false,
                        ]);
                    })
                    ->successNotificationTitle('Şifre güncellendi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
