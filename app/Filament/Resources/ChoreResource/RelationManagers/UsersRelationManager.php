<?php

namespace App\Filament\Resources\ChoreResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use App\Models\Family;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Assigned Users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_by')
                    ->formatStateUsing(fn ($state) => 
                        User::find($state)->name
                    ),
                Tables\Columns\TextColumn::make('performed')
                    ->dateTime('d/m/Y'),
                Tables\Columns\IconColumn::make('confirmed')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                $familyId = $this->getOwnerRecord()->family->id;  // Get this chore's family

                                // Get all users in family
                                return Family::find($familyId)
                                        ->members()
                                        ->pluck('name', 'user_id');
                            })
                            ->required(),
                        Forms\Components\Select::make('assigned_by')
                            ->options(function (callable $get) {
                                $familyId = $this->getOwnerRecord()->family->id;  // Get this chore's family

                                // Get all adults in family
                                return Family::find($familyId)
                                    ->adults()
                                    ->pluck('name', 'user_id');
                            })
                            ->required(),
                        Forms\Components\DateTimePicker::make('performed')
                            ->format('Y-m-d H:i:s'),
                        Forms\Components\Checkbox::make('confirmed'),
                        Forms\Components\TextInput::make('comment')
                            ->maxLength(255),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
