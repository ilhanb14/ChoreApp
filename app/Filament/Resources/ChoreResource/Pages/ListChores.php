<?php

namespace App\Filament\Resources\ChoreResource\Pages;

use App\Filament\Resources\ChoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChores extends ListRecords
{
    protected static string $resource = ChoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
