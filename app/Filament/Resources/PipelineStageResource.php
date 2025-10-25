<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PipelineStageResource\Pages;
use App\Models\PipelineStage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PipelineStageResource extends Resource
{
    protected static ?string $model = PipelineStage::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    
    protected static ?string $navigationLabel = 'Etapas do Pipeline';
    
    protected static ?string $modelLabel = 'Etapa';
    
    protected static ?string $pluralModelLabel = 'Etapas do Pipeline';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome da Etapa')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('order')
                    ->label('Ordem')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Ordem de exibição no pipeline (menor número aparece primeiro)'),
                
                Forms\Components\ColorPicker::make('color')
                    ->label('Cor')
                    ->default('#3b82f6'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\ColorColumn::make('color')
                    ->label('Cor'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('leads_count')
                    ->label('Nº de Leads')
                    ->counts('leads')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),
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
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePipelineStages::route('/'),
        ];
    }
}
