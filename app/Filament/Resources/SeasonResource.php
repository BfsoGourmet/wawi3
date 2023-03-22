<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeasonResource\Pages;
use App\Models\Season;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Season';

    protected static ?string $modelPluralLabel = 'Season';

    protected static ?string $navigationLabel = 'Seasons';

    protected static ?string $navigationGroup = 'Product components';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Season')
                    ->required(),

                Forms\Components\Repeater::make('seaonDates')
                    ->label('Season dates')
                    ->relationship('seasonDates')
                    ->createItemButtonLabel('Add season date')
                    ->schema([
                        Forms\Components\DateTimePicker::make('date_from')
                            ->label('From')
                            ->required(),
                        Forms\Components\DateTimePicker::make('date_until')
                            ->label('Until')
                            ->after(function ($get) { return $get('date_from'); })
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Season')
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
            'index' => Pages\ListSeasons::route('/'),
            'create' => Pages\CreateSeason::route('/create'),
            'edit' => Pages\EditSeason::route('/{record}/edit'),
        ];
    }    
}
