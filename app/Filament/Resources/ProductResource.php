<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Allergy;
use App\Models\Category;
use App\Models\Product;
use App\Models\Season;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $modelPluralLabel = 'Products';

    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required(),

                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->length(8)
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->rules(['alpha_dash'])
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),

                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('short_description')
                        ->label('Short description')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                ]),

                Forms\Components\Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->multiple(false)
                    ->directory('product_images')
                    ->image(),

                Forms\Components\Card::make([           
                    Forms\Components\TextInput::make('price')
                    ->label('Normal price')
                    ->minValue(0)
                    ->suffix('CHF')
                    ->numeric()
                    ->columnSpan(2)
                    ->required(),

                    Forms\Components\Repeater::make('seasons')
                        ->relationship('seasonPrices')
                        ->createItemButtonLabel('Add seasonal price')
                        ->minItems(0)
                        ->schema([
                            Forms\Components\Select::make('season_id')
                                ->label('season')
                                ->options(Season::all()->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\TextInput::make('seasonal_price')
                                ->minValue(0)
                                ->suffix('CHF')
                                ->numeric()
                                ->required()
                        ]),

                    Forms\Components\Repeater::make('discounts')
                        ->relationship('discounts')
                        ->createItemButtonLabel('Add discount')
                        ->minItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('discount_price')
                                ->label('Discount Price')
                                ->minValue(0)
                                ->suffix('CHF')
                                ->numeric()
                                ->required(),
                            Forms\Components\DateTimePicker::make('discount_from')
                                ->label('From')
                                ->required(),
                            Forms\Components\DateTimePicker::make('discount_until')
                                ->label('Until')
                                ->after(function ($get) { return $get('discount_from'); })
                                ->required()
                        ]),
                ])->columns(),

                Forms\Components\Card::make([
                    Forms\COmponents\Builder\Block::make('food_properties')
                    ->schema([
                        Forms\Components\TextInput::make('calories')
                        ->label('Calories')
                        ->suffix('kCal')
                        ->minValue(0)
                        ->numeric()
                        ->maxValue(9999.99),
                        Forms\Components\TextInput::make('sugar_in_calories')
                            ->label('Sugar')
                            ->suffix('cal')
                            ->minValue(0)
                            ->maxValue(999.99)
                            ->numeric(),
                        Forms\Components\Checkbox::make('is_vegetarian')
                            ->label('Vegetarian'),
                        Forms\Components\Checkbox::make('is_vegan')
                            ->label('Vegan')
                    ]),
                    Forms\Components\Builder\Block::make('allergies')
                    ->schema([
                        Forms\Components\Select::make('allergies')
                            ->multiple()
                            ->relationship('allergies', 'name')
                            ->options(Allergy::all()->pluck('name', 'id'))
                            ->required(),
                    ]),
                ])->columns(2),

                Forms\Components\Repeater::make('supplierStocks')
                    ->relationship('supplierStocks')
                    ->schema([
                        Forms\Components\Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::all()->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('stock')
                            ->numeric()
                            ->required()
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make('short_description')->label('Short description'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\BadgeColumn::make('is_vegetarian')->label('Vegetarian')
                    ->formatStateUsing(function ($state) { return $state ? 'Yes' : 'No'; })
                    ->color(function ($state) { return $state ? 'success' : 'warning'; }),
                Tables\Columns\BadgeColumn::make('is_vegan')->label('Vegan')
                    ->formatStateUsing(function ($state) { return $state ? 'Yes' : 'No'; })
                    ->color(function ($state) { return $state ? 'success' : 'warning'; }),
                Tables\Columns\TextColumn::make('price')->label('Price')
                    ->formatStateUsing(function ($state) { return $state . ' CHF'; }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
