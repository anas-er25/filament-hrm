<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\SerializableClosure\Serializers\Native;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Pages\EditEmployee;
use App\Filament\Resources\EmployeeResource\Pages\ViewEmployee;
use App\Filament\Resources\EmployeeResource\Pages\ListEmployees;
use App\Filament\Resources\EmployeeResource\Pages\CreateEmployee;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->last_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name','last_name', 'middle_name'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return ['Country'=> $record->country->name];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['country']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 5 ? 'success' : 'warning';
    }
    
    public static function form(Form $form): Form
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

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Departement')
                ->relationship('departement', 'name')
                ->searchable()
                ->preload(),
                Filter::make('created_at')
    ->form([
        DatePicker::make('created_from'),
        DatePicker::make('created_until'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['created_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['created_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
    })

    ->indicateUsing(function (array $data): array {
        $indicators = []; 
        if ($data['created_from'] ?? null) {
            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString());
        }
 
        if ($data['created_until'] ?? null) {
            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString());
        }
 
        return $indicators;
    })
    ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                    ->success()
                    ->title('Employee deleted')
                    ->body('The Employee deleted successfully')
                )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Section::make('Relationships')
            ->schema([
                TextEntry::make('country.name')->label('Country name'),
                TextEntry::make('departement.name')->label('Departement'),
            ])->columns(2),
            Section::make('Details')
            ->schema([
                TextEntry::make('first_name')->label('First name'),
                TextEntry::make('last_name')->label('Last name'),
                TextEntry::make('date_hired')->label('Date hired'),
            ])->columns(3),
            Section::make('Address')
            ->schema([
                TextEntry::make('address')->label('Address'),
                TextEntry::make('zip_code')->label('Zip Code'),
            ])->columns(2),
            Section::make('Dates')
            ->schema([
                TextEntry::make('date_of_birth')->label('Date of Birth'),
                TextEntry::make('date_hired')->label('Date hired'),
            ])->columns(2),
            
        ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
