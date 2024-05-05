<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtractResource\Pages;
use App\Filament\Resources\ExtractResource\RelationManagers;
use App\Models\Extract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExtractResource extends Resource
{
    protected static ?string $model = Extract::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Extractes';
    protected static ?string $modelLabel = 'extracte';
    protected static ?string $pluralModelLabel = 'extractes';
    protected static ?string $navigationGroup = 'Vendes';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date_start')->dateTime()->label('Data inici')->sortable(),
                Tables\Columns\TextColumn::make('date_end')->dateTime()->label('Data fi')->sortable(),
                Tables\Columns\TextColumn::make('user.username')->label('Organitzador')->sortable(),
                Tables\Columns\TextColumn::make('product.title')->label('Producte')->sortable(),
                Tables\Columns\TextColumn::make('total_sales')->label('Total'),
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
            'index' => Pages\ListExtracts::route('/'),
            'create' => Pages\CreateExtract::route('/create'),
            'edit' => Pages\EditExtract::route('/{record}/edit'),
        ];
    }
}
