<?php

namespace App\Filament\Resources\CourierResource\Pages;

use App\Filament\Resources\CourierResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouriers extends ListRecords
{
    protected static string $resource = CourierResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
