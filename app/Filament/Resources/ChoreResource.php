<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChoreResource\Pages;
use App\Filament\Resources\ChoreResource\RelationManagers;
use App\Models\Chores;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Family;

class ChoreResource extends Resource
{
    protected static ?string $model = Chores::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('points')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Select::make('family_id')
                    ->relationship('family', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live(),
                Forms\Components\Select::make('created_by')
                    ->searchable()
                    ->options(function (callable $get) {
                        // Get currently selected family
                        $familyId = $get('family_id');
        
                        if (!$familyId) {  // If none selected, show no options yet
                            return [];
                        }
                        
                        // Get all adults in the family
                        return Family::find($familyId)
                            ->adults()
                            ->pluck('name', 'user_id');
                    })
                    ->required()
                    ->live(),
                Forms\Components\Checkbox::make('recurring')
                    ->inline(false)
                    ->live(),
                Forms\Components\Select::make('frequency')
                    ->options([
                            'daily' => 'Daily',
                            'weekly' => 'Weekly',
                            'monthly' => 'Monthly'
                        ])
                    ->required(fn (Forms\Get $get) => $get('recurring'))
                    ->disabled(fn (Forms\Get $get) => !$get('recurring')),
                Forms\Components\DateTimePicker::make('start_date')
                    ->format('Y-m-d H:i:s'),
                Forms\Components\DateTimePicker::make('deadline')
                    ->format('Y-m-d H:i:s'),
                Forms\Components\Textarea::make('description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('points')
                    ->numeric()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime('d/m/Y')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime('d/m/Y')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('recurring')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('frequency')
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => 
                        $state ? ucwords(str_replace('_', ' ', $state)) : '-'
                    ),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('family')
                    ->relationship('family', 'name')
                    ->preload(),
                Tables\Filters\TernaryFilter::make('deadline')
                    ->nullable(),
                Tables\Filters\TernaryFilter::make('recurring'),
                Tables\Filters\SelectFilter::make('frequency')
                    ->options([
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChores::route('/'),
            'create' => Pages\CreateChore::route('/create'),
            'edit' => Pages\EditChore::route('/{record}/edit'),
        ];
    }
}
