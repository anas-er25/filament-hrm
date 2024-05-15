<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make("RelationShips")
            ->schema([
                Forms\Components\Select::make('country_id')
                ->relationship(name : 'Country', titleAttribute: 'name')
                ->searchable()
                ->preload()
                ->native(false)
                ->live()
                ->afterStateUpdated(function (Set $set) { $set('state_id', null); $set('city_id', null);})
                ->required(),
                Forms\Components\Select::make('state_id')
                ->options(fn(Get $get): Collection => State::query()
                ->where('country_id', $get('country_id'))
                ->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->native(false)
                ->live()
                ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                ->required(),
                Forms\Components\Select::make('city_id')
                ->options(fn(Get $get): Collection => City::query()
                ->where('state_id', $get('state_id'))
                ->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->native(false)
                ->live()
                ->required(),
                Forms\Components\Select::make('departement_id')
                ->relationship(name : 'Departement', titleAttribute: 'name')
                ->searchable()
                ->preload()
                ->native(false)
                ->required(),
            
            ])->columns(2),
            Forms\Components\Section::make("User Details")
            ->description("Put the user details in.")
            ->schema([
                Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            
            ])->columns(3),
            Forms\Components\Section::make('User Address')
            ->schema([
                Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('zip_code')
                ->required()
                ->maxLength(255)
            ])->columns(2),
            Forms\Components\Section::make('Dates')
            ->schema([
            Forms\Components\DatePicker::make('date_of_birth')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y'),
            Forms\Components\DatePicker::make('date_hired')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y'),
            ])->columns(2)
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('departement.name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('first_name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('last_name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('middle_name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('address')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('zip_code')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('date_of_birth')
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('date_hired')
                ->date()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
