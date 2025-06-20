<?php

namespace App\Filament\Resources\ChoreResource\Pages;

use App\Filament\Resources\ChoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChore extends EditRecord
{
    protected static string $resource = ChoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
