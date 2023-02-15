<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllergyResource\Pages;
use App\Filament\Resources\AllergyResource\RelationManagers;
use App\Models\Allergy;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllergyResource extends Resource
{
    protected static ?string $model = Allergy::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Allergy';

    protected static ?string $modelPluralLabel = 'Allergies';

    protected static ?string $navigationLabel = 'Allergies';

    protected static ?string $navigationGroup = 'Product components';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
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
            'index' => Pages\ListAllergies::route('/'),
            'create' => Pages\CreateAllergy::route('/create'),
            'edit' => Pages\EditAllergy::route('/{record}/edit'),
        ];
    }    
}
