<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions;
use Action\ImportButtonAction;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Vendor\Filament\Filament\src\Pages\Action\ButtonAction;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            // Action\ButtonAction::make(),
            // ImportButtonAction::make(),
            Actions\CreateAction::make(),
        ];
    }
}
