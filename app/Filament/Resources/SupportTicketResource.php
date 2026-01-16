<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationLabel = 'Destek Talepleri';
    
    protected static ?string $navigationGroup = 'Yardım ve Destek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Talep Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('Ticket No')
                            ->disabled(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Bekleyen',
                                'in_progress' => 'İşlemde',
                                'waiting_user' => 'Kullanıcı Bekleniyor',
                                'resolved' => 'Çözüldü',
                                'closed' => 'Kapatıldı',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('priority')
                            ->label('Öncelik')
                            ->options([
                                'low' => 'Düşük',
                                'medium' => 'Orta',
                                'high' => 'Yüksek',
                                'urgent' => 'Acil',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('assigned_to')
                            ->label('Atanan Kişi')
                            ->relationship('assignedAdmin', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Müşteri Talebi')
                    ->schema([
                        Forms\Components\Placeholder::make('subject')
                            ->label('Konu')
                            ->content(fn ($record) => $record?->subject),
                        
                        Forms\Components\Placeholder::make('description')
                            ->label('Açıklama')
                            ->content(fn ($record) => $record?->description),
                    ]),
                
                Forms\Components\Section::make('Admin Yanıtı')
                    ->schema([
                        Forms\Components\Textarea::make('admin_response')
                            ->label('Yanıtınız')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('Ticket No')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('user_type')
                    ->label('Tip')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'customer' => 'Müşteri',
                        'provider' => 'Hizmet Veren',
                    })
                    ->colors([
                        'primary' => 'customer',
                        'success' => 'provider',
                    ]),
                
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'service' => 'Hizmet',
                        'payment' => 'Ödeme',
                        'technical' => 'Teknik',
                    })
                    ->colors([
                        'info' => 'service',
                        'warning' => 'payment',
                        'danger' => 'technical',
                    ]),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('Konu')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Durum')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'Bekleyen',
                        'in_progress' => 'İşlemde',
                        'waiting_user' => 'Kullanıcı Bekleniyor',
                        'resolved' => 'Çözüldü',
                        'closed' => 'Kapatıldı',
                    })
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'in_progress',
                        'info' => 'waiting_user',
                        'success' => 'resolved',
                        'secondary' => 'closed',
                    ]),
                
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Öncelik')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'low' => 'Düşük',
                        'medium' => 'Orta',
                        'high' => 'Yüksek',
                        'urgent' => 'Acil',
                    })
                    ->colors([
                        'secondary' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                        'danger' => 'urgent',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('assignedAdmin.name')
                    ->label('Atanan')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Bekleyen',
                        'in_progress' => 'İşlemde',
                        'waiting_user' => 'Kullanıcı Bekleniyor',
                        'resolved' => 'Çözüldü',
                        'closed' => 'Kapatıldı',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'service' => 'Hizmet',
                        'payment' => 'Ödeme',
                        'technical' => 'Teknik',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Öncelik')
                    ->options([
                        'low' => 'Düşük',
                        'medium' => 'Orta',
                        'high' => 'Yüksek',
                        'urgent' => 'Acil',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Yanıtla'),
                
                Tables\Actions\Action::make('close')
                    ->label('Kapat')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (SupportTicket $record) => $record->update(['status' => 'closed']))
                    ->visible(fn (SupportTicket $record) => $record->status !== 'closed'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('close')
                        ->label('Toplu Kapat')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'closed'])),
                    
                    Tables\Actions\BulkAction::make('assign')
                        ->label('Toplu Atama')
                        ->icon('heroicon-o-user')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('Atanacak Kişi')
                                ->relationship('assignedAdmin', 'name')
                                ->required(),
                        ])
                        ->action(fn (Collection $records, array $data) => 
                            $records->each->update(['assigned_to' => $data['assigned_to']])
                        ),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
}
