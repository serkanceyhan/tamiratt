<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Taleplerim';
    
    protected static ?string $navigationGroup = 'Yardım ve Destek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('ticket_number')
                    ->label('Ticket No')
                    ->content(fn ($record) => $record?->ticket_number),
                
                Forms\Components\Placeholder::make('status')
                    ->label('Durum')
                    ->content(fn ($record) => match($record?->status) {
                        'pending' => 'Bekleyen',
                        'in_progress' => 'İşlemde',
                        'waiting_user' => 'Kullanıcı Bekleniyor',
                        'resolved' => 'Çözüldü',
                        'closed' => 'Kapatıldı',
                        default => $record?->status
                    }),
                
                Forms\Components\Placeholder::make('subject')
                    ->label('Konu')
                    ->content(fn ($record) => $record?->subject),
                
                Forms\Components\Placeholder::make('description')
                    ->label('Açıklama')
                    ->content(fn ($record) => $record?->description),
                
                Forms\Components\Placeholder::make('admin_response')
                    ->label('Admin Yanıtı')
                    ->content(fn ($record) => $record?->admin_response ?? 'Henüz yanıt verilmedi')
                    ->visible(fn ($record) => filled($record?->admin_response)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('Ticket No')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'service' => 'Hizmet',
                        'payment' => 'Ödeme',
                        'technical' => 'Teknik',
                    }),
                
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
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detay'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSupportTickets::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Created via SupportCenter page
    }
}
