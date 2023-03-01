<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Season;
use App\Models\Supplier;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;

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
                    ->numeric()
                    ->columnSpan(2)
                    ->required(),

                    Forms\Components\Repeater::make('seasons')
                        ->relationship('seasonPrices')
                        ->schema([
                            Forms\Components\Select::make('season_id')
                                ->label('season')
                                ->options(Season::all()->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\TextInput::make('seasonal_price')
                                ->numeric()
                                ->required()
                        ]),

                    Forms\Components\Repeater::make('discounts')
                        ->relationship('discounts')
                        ->createItemButtonLabel('Add discount')
                        ->schema([
                            Forms\Components\TextInput::make('discount_price')
                                ->label('Discount Price')
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
                    ->required(),

                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('calories')
                    ->label('Calories in kcal')
                    ->numeric()
                    ->maxValue(9999.99)
                    ->required(),
                    Forms\Components\TextInput::make('sugar_in_calories')
                        ->label('Sugar in calories')
                        ->minValue(0)
                        ->maxValue(999.99)
                        ->numeric(),
                    Forms\Components\Checkbox::make('is_vegetarian')
                        ->label('Vegetarian'),
                    Forms\Components\Checkbox::make('is_vegan')
                        ->label('Vegan')
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make('short_description')->label('Short description'),
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
