<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OverdueActivitiesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        $query = Activity::query()
            ->overdue()
            ->with(['lead', 'user']);
        
        if (Auth::user()->isVendedor()) {
            $query->whereHas('lead', function (Builder $q) {
                $q->where('user_id', Auth::id());
            });
        }
        
        return $table
            ->heading('⚠️ Atividades Atrasadas')
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
                    ->badge()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Agendada para')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsável'),
                
                Tables\Columns\TextColumn::make('notes')
                    ->label('Anotações')
                    ->limit(50),
            ])
            ->actions([
                Tables\Actions\Action::make('complete')
                    ->label('Concluir')
                    ->icon('heroicon-o-check')
                    ->action(fn (Activity $record) => $record->update(['status' => 'completed']))
                    ->requiresConfirmation()
                    ->color('success'),
            ]);
    }
}
