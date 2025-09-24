<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Document Information')
                    ->icon('heroicon-m-document')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('serie')
                            ->options([
                                'F001' => 'F001',
                                'B001' => 'B001',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('correlativo')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => str_pad((Invoice::max('correlativo') ?? 0) + 1, 8, '0', STR_PAD_LEFT)),
                        Forms\Components\Select::make('tipo_doc')
                            ->options([
                                '01' => 'FACTURA',
                                '03' => 'BOLETA',
                            ])
                            ->required()
                            ->native(false),
                    ]),
                Forms\Components\Section::make('Relations')
                    ->icon('heroicon-m-link')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'id'),
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'id'),
                        Forms\Components\TextInput::make('igv_percentage')
                            ->required()
                            ->numeric()
                            ->default(18),
                    ]),

                Forms\Components\Section::make('Invoice Details')
                    ->icon('heroicon-m-shopping-cart')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('cantidad')
                                    ->numeric()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $cantidad = $get('cantidad') ?? 0;
                                        $precioUnitario = $get('precio_unitario') ?? 0;
                                        $igv = $cantidad * $precioUnitario * 0.18;
                                        $total = $cantidad * $precioUnitario + $igv;
                                        $set('igv', $igv);
                                        $set('total', $total);
                                    }),
                                Forms\Components\TextInput::make('precio_unitario')
                                    ->numeric()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $cantidad = $get('cantidad') ?? 0;
                                        $precioUnitario = $get('precio_unitario') ?? 0;
                                        $igv = $cantidad * $precioUnitario * 0.18;
                                        $total = $cantidad * $precioUnitario + $igv;
                                        $set('igv', $igv);
                                        $set('total', $total);
                                    }),
                                Forms\Components\TextInput::make('igv')
                                    ->numeric()
                                    ->disabled(), // Calculado automáticamente
                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->disabled(), // Calculado automáticamente
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->reorderableWithDragAndDrop(false)
                            ->cloneable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correlativo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_doc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_emision')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('igv_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('igv')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('xml_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cdr_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_respuesta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
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
