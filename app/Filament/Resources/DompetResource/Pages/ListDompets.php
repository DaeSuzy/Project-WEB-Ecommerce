<?php

namespace App\Filament\Resources\DompetResource\Pages;

use App\Filament\Resources\DompetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDompets extends ListRecords
{
    protected static string $resource = DompetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
