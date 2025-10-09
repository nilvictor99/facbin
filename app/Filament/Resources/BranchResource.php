<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use App\Services\Models\UbigeoService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tapp\FilamentCountryCodeField\Forms\Components\CountryCodeSelect;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('branch.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('branch.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('branch.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Branch information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'El nombre de la sucursal ya esta en uso',
                            ])
                            ->required()
                            ->maxLength(255)
                            ->reactive(),
                        Forms\Components\TextInput::make('id')
                            ->label(__('correlative'))
                            ->numeric()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'El codigo de la sucursal ya esta en uso, ingrese otro',
                            ])
                            ->default(fn () => Branch::nextCode())
                            ->required()
                            ->maxLength(255)
                            ->rule('digits_between:1,10')
                            ->reactive(),
                    ])->columns(2),
                Forms\Components\Repeater::make('address')
                    ->translateLabel()
                    ->relationship('address')
                    ->collapsible()
                    ->addable(false)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('ubigeo_cod')
                            ->translateLabel()
                            ->searchable()
                            ->required()
                            ->preload()
                            ->default('080101')
                            ->placeholder('Buscar por distrito o provincia')
                            ->getSearchResultsUsing(function (string $search) {
                                return app(UbigeoService::class)
                                    ->search($search)
                                    ->mapWithKeys(fn ($ubigeo) => [
                                        $ubigeo->cod_ubigeo => "{$ubigeo->district} ({$ubigeo->province}, {$ubigeo->departament})",
                                    ])
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $ubigeo = app(UbigeoService::class)
                                    ->search($value)
                                    ->first();

                                return $ubigeo ? "{$ubigeo->district} ({$ubigeo->province}, {$ubigeo->departament})" : '';
                            }),
                        Forms\Components\TextInput::make('address')
                            ->translateLabel()
                            ->required(),
                        Forms\Components\Textarea::make('reference')
                            ->translateLabel()
                            ->autosize()
                            ->rows(2)
                            ->maxLength(255),

                    ])->columns(3),
                Forms\Components\Repeater::make('contacts')
                    ->translateLabel()
                    ->relationship('contacts')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('contact_type')
                            ->translateLabel()
                            ->maxLength(50)
                            ->default('Oficina')
                            ->nullable(),
                        CountryCodeSelect::make('country_code')
                            ->default('+51')
                            ->translateLabel(),
                        Forms\Components\TextInput::make('contact_value')
                            ->translateLabel()
                            ->rules(['max:15'])
                            ->maxLength(15),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('id')
                    ->label(__('ubigeo'))
                    ->limit(25)
                    ->formatStateUsing(fn ($record) => $record->address?->ubigeo
                        ? $record->address->ubigeo->departament.' - '.$record->address->ubigeo->province.' - '.$record->address->ubigeo->district
                        : 'Ubigeo no disponible'),
                Tables\Columns\TextColumn::make('address')
                    ->translateLabel()
                    ->default('Dirección no disponible')
                    ->formatStateUsing(fn ($record) => $record->address?->address ?? 'No disponible')
                    ->limit(25)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('contacts')
                    ->translateLabel()
                    ->state(function ($record): string {
                        return $record->contacts()
                            ->latest()
                            ->first()
                            ?->contact_value ?? 'No disponible';
                    })
                    ->badge()
                    ->copyable()
                    ->limit(25),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
