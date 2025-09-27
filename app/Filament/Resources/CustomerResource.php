<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\IdentificationType;
use App\Services\Models\UbigeoService;
use App\Services\Utils\CodeGeneratorService;
use App\Services\Utils\IdentificationService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tapp\FilamentCountryCodeField\Forms\Components\CountryCodeSelect;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('customer.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('customer.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('customer.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Information'))
                    ->description(__('Main data'))
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->translateLabel()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(function () {
                                return app(CodeGeneratorService::class)->generate('alphanumeric');
                            })
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-arrow-path')
                                    ->action(function ($set) {
                                        $set('code', app(CodeGeneratorService::class)->generate('alphanumeric'));
                                    })
                            )
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Repeater::make('profile')
                    ->translateLabel()
                    ->relationship('profile')
                    ->deletable(false)
                    ->schema([
                        Forms\Components\Select::make('identification_type_id')
                            ->translateLabel()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->default(2)
                            ->relationship('identificationType', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('document_number')
                            ->translateLabel()
                            ->required()
                            ->unique(table: 'profiles', column: 'document_number', ignoreRecord: true)
                            ->disabled(fn (callable $get) => $get('is_disabled') ?? false)
                            ->afterStateHydrated(function (mixed $component, mixed $state, callable $set, string $context) {
                                if ($context === 'edit') {
                                    $set('is_disabled', true);
                                }
                            })
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('toggleEdit')
                                    ->icon(fn (callable $get) => $get('is_disabled') ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                                    ->tooltip(fn (callable $get) => $get('is_disabled') ? 'Habilitar edición' : 'Deshabilitar edición')
                                    ->action(function (callable $set, callable $get) {
                                        $set('is_disabled', ! $get('is_disabled'));
                                    })
                                    ->visible(fn (string $context): bool => $context === 'edit')
                            )
                            ->live()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-magnifying-glass')
                                    ->action(function (?string $state, callable $set, callable $get) {
                                        app(IdentificationService::class)->setFullNameAndAddress($state, $set, $get);
                                    })
                                    ->visible(
                                        fn (callable $get, string $context): bool => in_array($get('identification_type_id'), IdentificationType::dniRuc()) &&
                                            $context !== 'view' &&
                                            ($context !== 'edit' || ! ($get('is_disabled') ?? true))
                                    ),
                            )->extraAttributes(fn (Forms\Components\TextInput $component) => [
                                'wire:keydown.enter.prevent' => "mountFormComponentAction('{$component->getStatePath()}', 'generate')",
                            ])
                            ->maxLength(11),
                        Forms\Components\TextInput::make('full_name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->translateLabel()
                            ->autosize()
                            ->rows(1)
                            ->maxLength(255),

                    ])
                    ->minItems(1)
                    ->columns(3)
                    ->addable(false)
                    ->maxItems(1),
                Forms\Components\Repeater::make('address')
                    ->translateLabel()
                    ->relationship('address')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('ubigeo_cod')
                            ->translateLabel()
                            ->default('080101')
                            ->searchable()
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
                            ->translateLabel(),
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
                    ->schema([
                        Forms\Components\TextInput::make('contact_type')
                            ->translateLabel()
                            ->maxLength(50)
                            ->default('Personal')
                            ->nullable(),
                        CountryCodeSelect::make('country_code')
                            ->default('+51')
                            ->translateLabel(),
                        Forms\Components\TextInput::make('contact_value')
                            ->translateLabel()
                            ->tel()
                            ->rules(['max:15'])
                            ->maxLength(15),
                    ])
                    ->columns(3),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
