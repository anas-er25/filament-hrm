<?php

namespace App\Filament\App\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\EmployeeResource;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Employee created')
        ->body('The Employee created successfully');
    } 
}
