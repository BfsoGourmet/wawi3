<?php

namespace App\Filament\Resources\DeliveryResource\Pages;

use App\Filament\Resources\DeliveryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeliveries extends ListRecords
{
    protected static string $resource = DeliveryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
