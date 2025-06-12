<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use App\Models\Family;
use App\Enums\FamilyRole;

class FamiliesRelationManager extends RelationManager
{
    protected static string $relationship = 'families';

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
                    ->label('Family Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Role'),
                Tables\Columns\TextColumn::make('pivot.points')
                    ->label('Points')
                    ->numeric(),
                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Joined')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'adult' => 'Adult',
                        'child' => 'Child',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                $user = $this->getOwnerRecord();  // Get this user

                                // Get all families user is not in yet
                                return Family::whereDoesntHave('members', function (Builder $query) use ($user) {
                                    $query->where('users.id', $user->id);
                                })->pluck('name', 'id');
                            })
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Family Name'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return Family::create($data)->getKey();
                            }),
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'adult' => 'Adult',
                                'child' => 'Child',
                            ])
                            ->default('child')
                            ->required(),
                        Forms\Components\TextInput::make('points')
                            ->label('Points')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'adult' => 'Adult',
                                'child' => 'Child',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('points')
                            ->label('Points')
                            ->numeric()
                            ->required(),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
