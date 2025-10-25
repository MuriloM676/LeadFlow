<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LatestActivitiesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        $query = Activity::query()
            ->latest()
            ->limit(10)
            ->with(['lead', 'user']);
        
        if (Auth::user()->isVendedor()) {
            $query->whereHas('lead', function (Builder $q) {
                $q->where('user_id', Auth::id());
            });
        }
        
        return $table
            ->heading('📋 Últimas Atividades')
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('lead.contact_name')
                    ->label('Lead')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'call' => 'Chamada',
                        'meeting' => 'Reunião',
                        'message' => 'Mensagem',
                        'email' => 'E-mail',
                        default => $state,
                    })
                    ->badge(),
                
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'scheduled' => 'Agendada',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsável'),
            ]);
    }
}
