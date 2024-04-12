<?php

namespace App\Filament\Resources\ExtractResource\Pages;

use App\Filament\Resources\ExtractResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExtracts extends ListRecords
{
    protected static string $resource = ExtractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
