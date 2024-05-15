<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DepartementResource;

class EditDepartement extends EditRecord
{
    protected static string $resource = DepartementResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Departement updated')
        ->body('The Departement updated successfully');
    } 

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
