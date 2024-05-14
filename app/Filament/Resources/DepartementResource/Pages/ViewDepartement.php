<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use App\Filament\Resources\DepartementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDepartement extends ViewRecord
{
    protected static string $resource = DepartementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
