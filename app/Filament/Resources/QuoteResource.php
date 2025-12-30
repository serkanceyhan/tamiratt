<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationGroup = 'Ayarlar';
    
    protected static ?string $navigationLabel = 'Teklif Talepleri';
    
    protected static ?string $pluralLabel = 'Teklif Talepleri';
    
    protected static ?string $modelLabel = 'Teklif Talebi';

    /**
     * Data scoping: Service Providers only see their own quotes
     * 
     * MARKETPLACE MODEL (Future Implementation):
     * - Service providers DON'T create quotes, they PURCHASE them
     * - Filter should be: quotes they've paid to unlock (quote_service_provider pivot table)
     * - Or: quotes matching their expertise areas
     * 
     * Current (Temporary): Using email matching for testing purposes
     * 
     * @TODO: Replace with proper marketplace filtering:
     * 1. Create quote_service_provider pivot table
     * 2. Add expertise/service matching logic
     * 3. Filter: ->whereHas('purchased_by', fn($q) => $q->where('user_id', auth()->id()))
     *          ->orWhere('service_category_id', auth()->user()->expertise)
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(auth()->user()->hasRole('service_provider'), function ($query) {
                // TEMPORARY: Email matching (will be replaced with marketplace logic)
                return $query->where('email', auth()->user()->email);
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->label('Şirket Adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Ad Soyad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-posta')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('service_type')
                    ->label('Hizmet Türü')
                    ->options([
                        'Sandalye Döşeme & Yenileme' => 'Sandalye Döşeme & Yenileme',
                        'Masa Cilalama & Onarım' => 'Masa Cilalama & Onarım',
                        'Mekanizma Tamiri' => 'Mekanizma Tamiri',
                        'Komple Ofis Yenileme' => 'Komple Ofis Yenileme',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('message')
                    ->label('Mesaj')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('file_path')
                    ->label('Dosya Yolu')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Şirket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('İsim')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Hizmet')
                    ->badge()
                    ->sortable(),
                Tables\Columns\IconColumn::make('file_path')
                    ->label('Dosya')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark')
                    ->getStateUsing(fn ($record) => !empty($record->file_path)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_type')
                    ->label('Hizmet Türü')
                    ->options([
                        'Sandalye Döşeme & Yenileme' => 'Sandalye Döşeme & Yenileme',
                        'Masa Cilalama & Onarım' => 'Masa Cilalama & Onarım',
                        'Mekanizma Tamiri' => 'Mekanizma Tamiri',
                        'Komple Ofis Yenileme' => 'Komple Ofis Yenileme',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'view' => Pages\ViewQuote::route('/{record}'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
