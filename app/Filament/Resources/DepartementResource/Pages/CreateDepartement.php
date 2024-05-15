<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DepartementResource;

class CreateDepartement extends CreateRecord
{
    protected static string $resource = DepartementResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Departement created')
        ->body('The Departement created successfully');
    } 
}
