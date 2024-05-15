<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('User created')
        ->body('The User created successfully');
    } 
}
