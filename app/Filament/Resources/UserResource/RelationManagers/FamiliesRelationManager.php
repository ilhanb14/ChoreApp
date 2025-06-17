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
                Tables\Filters\SelectFilter::make('role')   // Filter families where user has a specific role
                    ->label('Role')
                    ->options([
                        'adult' => 'Adult',
                        'child' => 'Child',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make() // Attach = add user to family
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()  // Select options
                            ->searchable()
                            ->preload()
                            ->options(function () { // Families to choose from
                                $user = $this->getOwnerRecord();  // Get this user

                                // Get all families user is not in yet
                                return Family::whereDoesntHave('members', function (Builder $query) use ($user) {
                                    $query->where('users.id', $user->id);
                                })->pluck('name', 'id');
                            })
                            ->createOptionForm([    // Create a new family to add user to
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Family Name'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return Family::create($data)->getKey();
                            }),
                        Forms\Components\Select::make('role')   // Pivot data for this relation
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
                Tables\Actions\EditAction::make()   // Edit pivot data for a relation
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
                Tables\Actions\DetachAction::make(),    // Detach = remove user from family
            ])
            ->bulkActions([     // Allow selecting multiple rows to do something
                Tables\Actions\BulkActionGroup::make([  // Remove multiple rows (= remove user from multiple families)
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
