<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Currency;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('product.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('product.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('product.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Basic Information'))
                    ->icon('heroicon-m-information-circle')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->translateLabel()
                            ->translateLabel(),
                    ]),
                Forms\Components\Section::make(__('Inventory'))
                    ->icon('heroicon-m-archive-box')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('measure_unit_id')
                            ->translateLabel()
                            ->relationship('measureUnit', 'name')
                            ->required()
                            ->default(1)
                            ->native(false),
                        Forms\Components\TextInput::make('stock')
                            ->translateLabel()
                            ->required()
                            ->numeric()
                            ->default(0),

                    ]),
                Forms\Components\Section::make(__('Pricing'))
                    ->icon('heroicon-m-currency-dollar')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('sale_price')
                            ->translateLabel()
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('purchase_price')
                            ->translateLabel()
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('currency_id')
                            ->translateLabel()
                            ->relationship('currency', 'name')
                            ->required()
                            ->default(1)
                            ->native(false),
                        Forms\Components\TextInput::make('characters')
                            ->translateLabel(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('measureUnit.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->translateLabel()
                    ->badge()
                    ->color('success')
                    ->prefix(fn (Product $record): string => (Currency::where('code', $record->currency)->value('symbol') ?? 'S/.').' '),
                Tables\Columns\IconColumn::make('active')
                    ->translateLabel()
                    ->boolean(),
                Tables\Columns\TextColumn::make('currency.code')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->translateLabel(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
