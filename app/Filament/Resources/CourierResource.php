<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourierResource\Pages;
use App\Filament\Resources\CourierResource\RelationManagers;
use App\Models\Courier;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    
    protected static ?string $navigationGroup = 'Product components';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('last_name')->required(),
                TextInput::make('first_name')->required(),
                TextInput::make('phone_number')->required()
                    ->tel()
                    ->telRegex('/^(?:\+41|0)[ ]?(?:\d[ ]?){8,9}$/'),
            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('admin')) {
            return parent::getEloquentQuery();
        }

        if (auth()->user()->hasRole('courier')) {
            return parent::getEloquentQuery()->where('id', auth()->user()->id);
        }

        return parent::getEloquentQuery()->max(0);
    }

    public static function table(Table $table): Table
    {


        return $table
            ->columns([
                TextColumn::make('first_name'),
                TextColumn::make('last_name'),
                TextColumn::make('phone_number'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListCouriers::route('/'),
            'create' => Pages\CreateCourier::route('/create'),
            'edit' => Pages\EditCourier::route('/{record}/edit'),
        ];
    }    
}
