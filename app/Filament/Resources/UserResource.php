<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\IdentificationType;
use App\Models\User;
use App\Services\Models\UbigeoService;
use App\Services\Utils\IdentificationService;
use App\Services\Utils\PasswordGenerator;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentCountryCodeField\Forms\Components\CountryCodeSelect;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('user.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('user.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('user.User_information'))
                    ->icon('heroicon-m-user')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->translateLabel()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ]),

                Section::make(__('user.Security'))
                    ->icon('heroicon-m-adjustments-vertical')
                    ->collapsible()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->visibleOn('create')
                            ->revealable()
                            ->required()
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-key')
                                    ->action(function ($set) {
                                        $set('password', app(PasswordGenerator::class)->generate());
                                    })
                            )
                            ->maxLength(255),
                    ]),
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
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->translateLabel()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->translateLabel()
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
