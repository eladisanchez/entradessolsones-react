<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Get;
use App\Models\Product;
use Str;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = ProductResource::class;

    public function getSteps(): array
    {
        return [
            Step::make('Informació bàsica')
                ->icon('heroicon-m-information-circle')
                ->schema([
                    TextInput::make('title')
                        ->label('Títol')
                        ->required()
                        ->live()
                        ->columnSpan(6),

                    //->afterStateUpdated(fn($state, callable $set) => $set('name', Str::slug($state))),
                    // TextInput::make('name')
                    //     ->label('URL')
                    //     ->disabled()
                    //     ->required()
                    //     ->unique(Product::class, 'name', fn($record) => $record),
                    Select::make('target')
                        ->label('Tipus d\'activitat')
                        ->options(config('tickets.types'))
                        ->required()
                        ->columnSpan(3),
                    Select::make('category_id')
                        ->label('Categoria')
                        ->relationship(name: 'category', titleAttribute: 'title')
                        ->searchable()
                        ->required()
                        ->columnSpan(3),
                    Toggle::make('is_pack')
                        ->label('És un pack')
                        ->helperText('El producte estarà compost de varis productes')
                        ->columnSpan(6),
                ])->columns(6),
            Step::make('Descripció i horaris')
                ->icon('heroicon-m-clock')
                ->schema([
                    Fieldset::make('Català')
                        ->columns(2)
                        ->schema([
                            TextInput::make('summary')
                                ->label('Resum')
                                ->maxLength(255)
                                ->columnSpan('full'),
                            RichEditor::make('description_ca')
                                ->label('Descripció'),
                            RichEditor::make('schedule_ca')
                                ->label('Horaris i informació d\'interès'),
                        ]),
                    Fieldset::make('Castellà')
                        ->columns(2)
                        ->schema([
                            TextInput::make('title_es')
                                ->label('Títol'),
                            TextInput::make('summary_es')
                                ->label('Resum')
                                ->maxLength(255)
                                ->columnSpan('full'),
                            RichEditor::make('description_es')
                                ->label('Descripció'),
                            RichEditor::make('schedule_es')
                                ->label('Horaris i informació d\'interès'),
                        ])
                ]),
            Step::make('Espai i condicions de venda')

                ->icon('heroicon-m-map-pin')
                ->schema([
                    Select::make('venue_id')
                        ->label('Espai')
                        ->relationship(name: 'venue', titleAttribute: 'name')
                        ->searchable()
                        ->helperText("Escollint un espai el producte serà un esdeveniment amb entrades numerades.")
                        ->columnSpan(3),
                    TextInput::make('lloc')
                        ->label("Lloc de l'esdeveniment / punt inicial de la visita")
                        ->columnSpan(3),
                    TextInput::make('min_tickets')
                        ->label('Mínim entrades')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->step(1)
                        ->helperText("Mínim d'entrades que s'han de reservar per comanda.")
                        ->suffix('entrades')
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('max_tickets')
                        ->label('Màxim entrades')
                        ->numeric()
                        ->minValue(1)
                        ->default(10)
                        ->step(1)
                        ->helperText("Màxim d'entrades que es poden reservar per comanda.")
                        ->suffix('entrades')
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('limitHores')
                        ->label('Tancament venda')
                        ->numeric()
                        ->minValue(0)
                        ->default(2)
                        ->step(1)
                        ->helperText("Fins quantes hores abans de la sessió es poden adquirir entrades online")
                        ->suffix('hores')
                        ->required()
                        ->columnSpan(2),
                    Toggle::make('qr')
                        ->label('Entrades amb QR')
                        ->helperText('Habilita la lectura de QR per controlar l\'accés')
                        ->live()
                        ->columnSpan(6),
                    Fieldset::make('Lectura de QR')
                        ->schema([
                            TextInput::make('validation_start')
                                ->label('Inici lectura')
                                ->numeric()
                                ->minValue(0)
                                ->step(1)
                                ->helperText("A partir de quants minuts abans de la funció els QR són vàlids")
                                ->suffix('minuts')
                                ->columnSpan(2),
                            TextInput::make('validation_end')
                                ->label('Fi lectura')
                                ->numeric()
                                ->minValue(0)
                                ->step(1)
                                ->helperText("Després de quants minuts de l'hora d'inici els QR deixen de ser vàlids")
                                ->suffix('minuts')
                                ->columnSpan(2),
                        ])->columns(6)->hidden(fn(Get $get) => $get('qr') !== true),
                    Fieldset::make('Mesures Covid')
                        ->schema([
                            Toggle::make('social_distance')
                                ->label('Distància social')
                                ->helperText('Habilita el bloqueig de butaques adjacents d\'una comanda.')
                                ->live()
                                ->columnSpan(2),
                            TextInput::make('aforament')
                                ->label('Aforament màxim')
                                ->numeric()
                                ->minValue(0)
                                ->step(1)
                                ->helperText("La venda es tancarà a l'arribar al límit de percentatge d'aforament permès")
                                ->suffix('%')
                                ->columnSpan(2),
                        ])->columns(6),
                ])->columns(6),
        ];
    }

}
