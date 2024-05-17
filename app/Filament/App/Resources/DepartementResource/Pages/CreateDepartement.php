<?php

namespace App\Filament\App\Resources\DepartementResource\Pages;

use App\Filament\App\Resources\DepartementResource;
use Filament\Actions;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

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
