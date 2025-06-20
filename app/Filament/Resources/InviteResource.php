<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InviteResource\Pages;
use App\Filament\Resources\InviteResource\RelationManagers;
use App\Models\Invite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use App\Models\Family;

class InviteResource extends Resource
{
    protected static ?string $model = Invite::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inviter_id')
                    ->preload()
                    ->searchable()
                    ->options(function () {
                        // Get all users that are in at least one family
                        return User::has('families')->pluck('name', 'id');
                    })
                    ->live(),
                Forms\Components\Select::make('family_id')
                    ->searchable()
                    ->options(function (callable $get) {
                        // Get currently selected inviter
                        $inviterId = $get('inviter_id');
        
                        if (!$inviterId) {  // If none selected, show no options yet
                            return [];
                        }
                        
                        // Get all families of the inviter user
                        return User::find($inviterId)
                            ->families()
                            ->pluck('name', 'family_id');
                    })
                    ->live(),
                Forms\Components\Select::make('invited_id')
                    ->searchable()
                    ->options(function (callable $get) {
                        // Get currently selected family
                        $familyId = $get('family_id');
        
                        if (!$familyId) {  // If none selected, show no options yet
                            return [];
                        }
                        
                        // Get all users not yet in the family
                        return User::whereDoesntHave('families', function (Builder $query) use ($familyId) {
                                    $query->where('families.id', $familyId);
                                })->pluck('name', 'id');
                    }),
                Forms\Components\Select::make('role')
                    ->options([
                        'adult' => 'Adult',
                        'child' => 'Child',
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'denied' => 'Denied'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inviter.name')
                    ->label('Inviter')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invited.name')
                    ->label('Invited')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family.name')
                    ->label('Family')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->formatStateUsing(fn ($state) => 
                        $state ? ucwords(str_replace('_', ' ', $state)) : '-'
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => 
                        $state ? ucwords(str_replace('_', ' ', $state)) : '-'
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent on')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('family')
                    ->relationship('family', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('inviter')
                    ->relationship('inviter', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'denied' => 'Denied',
                    ])
                    ->preload(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvites::route('/'),
            'create' => Pages\CreateInvite::route('/create'),
            'edit' => Pages\EditInvite::route('/{record}/edit'),
        ];
    }
}
