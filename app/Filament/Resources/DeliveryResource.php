<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Delivery';

    protected static ?string $modelPluralLabel = 'Deliveries';

    protected static ?string $navigationLabel = 'Deliveries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("fname"),
                TextInput::make("lname"),
                TextInput::make("address"),
                TextInput::make("country"),
                TextInput::make("zip"),
                Forms\Components\Repeater::make('deliveryProducts')
                        ->relationship('deliveryProducts')
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Product')
                                ->options(Product::all()->pluck('title', 'id'))
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->suffix('x')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('price')
                                ->numeric()
                                ->required()
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("fname")->label("First Name"),
                TextColumn::make("lname")->label("Last Name"),
                TagsColumn::make('products')->label('Products')->getStateUsing(fn ($record) => $record->products->map(fn ($product) => $product->pivot->amount . 'x ' . $product->title)->toArray()),
                TextColumn::make('price')->getStateUsing(fn($record) => $record->getTotalPrice() . ' CHF')
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }    
}
