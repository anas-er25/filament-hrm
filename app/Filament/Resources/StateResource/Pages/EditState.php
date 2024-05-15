<?php

namespace App\Filament\Resources\StateResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\StateResource;

class EditState extends EditRecord
{
    protected static string $resource = StateResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Sate updated')
        ->body('The State updated successfully');
    } 

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
