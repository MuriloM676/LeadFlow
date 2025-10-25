<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Leads';
    
    protected static ?string $modelLabel = 'Lead';
    
    protected static ?string $pluralModelLabel = 'Leads';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Contato')
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Nome do Contato')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('company')
                            ->label('Empresa')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefone')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Detalhes do Lead')
                    ->schema([
                        Forms\Components\Select::make('source')
                            ->label('Origem')
                            ->options([
                                'website' => 'Website',
                                'referral' => 'Indicação',
                                'social_media' => 'Redes Sociais',
                                'cold_call' => 'Cold Call',
                                'email_campaign' => 'Campanha de E-mail',
                                'event' => 'Evento',
                                'other' => 'Outro',
                            ])
                            ->required()
                            ->default('other'),
                        
                        Forms\Components\Select::make('user_id')
                            ->label('Responsável')
                            ->options(User::where('role', 'vendedor')->pluck('name', 'id'))
                            ->required()
                            ->default(fn() => Auth::id())
                            ->searchable(),
                        
                        Forms\Components\Select::make('pipeline_stage_id')
                            ->label('Etapa do Pipeline')
                            ->options(PipelineStage::where('is_active', true)->orderBy('order')->pluck('name', 'id'))
                            ->required()
                            ->default(fn() => PipelineStage::orderBy('order')->first()?->id),
                        
                        Forms\Components\DatePicker::make('first_contact_date')
                            ->label('Data do Primeiro Contato')
                            ->required()
                            ->default(now()),
                        
                        Forms\Components\Textarea::make('needs_summary')
                            ->label('Resumo da Necessidade')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Contato')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('company')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('source')
                    ->label('Origem')
                    ->colors([
                        'primary' => 'website',
                        'success' => 'referral',
                        'warning' => 'social_media',
                        'danger' => 'cold_call',
                        'info' => 'email_campaign',
                        'secondary' => 'event',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'website' => 'Website',
                        'referral' => 'Indicação',
                        'social_media' => 'Redes Sociais',
                        'cold_call' => 'Cold Call',
                        'email_campaign' => 'Campanha',
                        'event' => 'Evento',
                        default => 'Outro',
                    }),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsável')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('pipelineStage.name')
                    ->label('Etapa')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_opportunity_value')
                    ->label('Valor Total')
                    ->money('BRL')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->leftJoin('opportunities', 'leads.id', '=', 'opportunities.lead_id')
                            ->selectRaw('leads.*, COALESCE(SUM(opportunities.estimated_value), 0) as total_value')
                            ->groupBy('leads.id')
                            ->orderBy('total_value', $direction);
                    }),
                
                Tables\Columns\IconColumn::make('has_overdue')
                    ->label('Atrasado')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->getStateUsing(fn (Lead $record): bool => $record->hasOverdueActivities()),
                
                Tables\Columns\TextColumn::make('first_contact_date')
                    ->label('Primeiro Contato')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pipeline_stage_id')
                    ->label('Etapa')
                    ->options(PipelineStage::pluck('name', 'id')),
                
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Responsável')
                    ->options(User::where('role', 'vendedor')->pluck('name', 'id'))
                    ->visible(fn () => Auth::user()->isGestor()),
                
                Tables\Filters\SelectFilter::make('source')
                    ->label('Origem')
                    ->options([
                        'website' => 'Website',
                        'referral' => 'Indicação',
                        'social_media' => 'Redes Sociais',
                        'cold_call' => 'Cold Call',
                        'email_campaign' => 'Campanha de E-mail',
                        'event' => 'Evento',
                        'other' => 'Outro',
                    ]),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'kanban' => Pages\KanbanLeads::route('/kanban'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Se for vendedor, mostrar apenas seus leads
        if (Auth::user()->isVendedor()) {
            $query->where('user_id', Auth::id());
        }
        
        return $query;
    }
}
