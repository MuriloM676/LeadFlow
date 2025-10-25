<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static ?string $navigationLabel = 'Atividades';
    
    protected static ?string $modelLabel = 'Atividade';
    
    protected static ?string $pluralModelLabel = 'Atividades';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lead_id')
                    ->label('Lead')
                    ->options(function () {
                        $query = Lead::query();
                        if (Auth::user()->isVendedor()) {
                            $query->where('user_id', Auth::id());
                        }
                        return $query->get()->pluck('contact_name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'call' => 'Chamada',
                        'meeting' => 'Reunião',
                        'message' => 'Mensagem',
                        'email' => 'E-mail',
                    ])
                    ->required()
                    ->default('call'),
                
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Data/Hora Agendada')
                    ->required()
                    ->default(now()),
                
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Agendada',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                    ])
                    ->required()
                    ->default('scheduled'),
                
                Forms\Components\Textarea::make('notes')
                    ->label('Anotações')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.contact_name')
                    ->label('Lead')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'call',
                        'success' => 'meeting',
                        'warning' => 'message',
                        'info' => 'email',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'call' => 'Chamada',
                        'meeting' => 'Reunião',
                        'message' => 'Mensagem',
                        'email' => 'E-mail',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Agendada para')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'scheduled' => 'Agendada',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('is_overdue')
                    ->label('Atrasada')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-circle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-check-circle')
                    ->falseColor('success')
                    ->getStateUsing(fn (Activity $record): bool => $record->isOverdue()),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsável')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'call' => 'Chamada',
                        'meeting' => 'Reunião',
                        'message' => 'Mensagem',
                        'email' => 'E-mail',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Agendada',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                    ]),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('Atrasadas')
                    ->query(fn (Builder $query): Builder => $query->overdue()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActivities::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Se for vendedor, mostrar apenas atividades dos seus leads
        if (Auth::user()->isVendedor()) {
            $query->whereHas('lead', function (Builder $q) {
                $q->where('user_id', Auth::id());
            });
        }
        
        return $query;
    }
}
