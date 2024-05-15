<?php

namespace App\Filament\Resources\CityResource\Pages;

use Filament\Actions;
use App\Filament\Resources\CityResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('City updated')
        ->body('The City updated successfully');
    } 

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
