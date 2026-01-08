<?php

namespace App\Filament\Provider\Widgets;

use App\Models\Provider;
use App\Models\ServiceRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RecentLeads extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Son İş Fırsatları';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Konum')
                    ->icon('heroicon-m-map-pin'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M, H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_unlocked')
                    ->label('Durum')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-open')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => $this->isUnlocked($record)),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Detay')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.provider.resources.leads.view', $record)),
            ])
            ->emptyStateHeading('Henüz iş fırsatı yok')
            ->emptyStateDescription('Bölgenize uygun talepler geldiğinde burada listelenecek.')
            ->emptyStateIcon('heroicon-o-briefcase')
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }

    protected function getTableQuery(): Builder
    {
        $provider = $this->getProvider();
        
        if (!$provider) {
            return ServiceRequest::query()->whereRaw('1 = 0'); // Empty result
        }

        // Get service requests matching provider's service areas and categories
        return ServiceRequest::query()
            ->where('status', 'open')
            ->when($provider->service_areas, function ($query) use ($provider) {
                // Match by location if provider has service areas
                $query->whereIn('location_id', $provider->service_areas);
            })
            ->when($provider->service_categories, function ($query) use ($provider) {
                // Match by service category
                $query->whereIn('service_id', $provider->service_categories);
            })
            ->with(['service', 'location'])
            ->latest();
    }

    protected function getProvider(): ?Provider
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        return Provider::where('user_id', $user->id)->first();
    }

    protected function isUnlocked($record): bool
    {
        $provider = $this->getProvider();
        if (!$provider) {
            return false;
        }
        
        return $provider->hasPurchasedQuote($record->id);
    }
}
