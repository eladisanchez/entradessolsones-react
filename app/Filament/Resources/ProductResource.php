<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\Rate;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Livewire\Attributes\Layout;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Productes';
    protected static ?string $modelLabel = 'producte';
    protected static ?string $pluralModelLabel = 'productes';
    protected static ?string $navigationGroup = 'Entrades';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        $venue = $form->model->venue ?? null;
        $id = $form->model->id ?? null;
        return $form
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make('Informació')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Títol')
                                    ->required()
                                    ->live()
                                    ->columnSpan(6),
                                TextInput::make('name')
                                    ->label('URL del producte')
                                    ->required()
                                    ->unique(Product::class, 'name', fn($record) => $record)
                                    ->columnSpan(6),
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
                                FileUpload::make('image')
                                    ->image()
                                    ->label('Imatge')
                                    ->disk('local')
                                    ->directory('thumbnails')
                                    ->columnSpan(3)
                            ])->columns(6),
                        Tabs\Tab::make('Descripció i horaris')
                            ->icon('heroicon-m-clock')
                            ->schema([
                                TextInput::make('summary')
                                    ->label('Resum')
                                    ->maxLength(255)
                                    ->required()
                                    ->columnSpan('full'),
                                RichEditor::make('description')
                                    ->label('Descripció'),
                                RichEditor::make('schedule')
                                    ->label('Horaris i informació d\'interès'),
                            ]),
                        Tabs\Tab::make('Espai i condicions de venda')
                            ->icon('heroicon-m-map-pin')
                            ->schema([
                                Select::make('venue_id')
                                    ->label('Espai')
                                    ->relationship(name: 'venue', titleAttribute: 'name')
                                    ->searchable()
                                    ->helperText("Escollint un espai el producte serà un esdeveniment amb entrades numerades.")
                                    ->columnSpan(3),
                                TextInput::make('place')
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
                        Tabs\Tab::make('Entrades')
                            ->icon('heroicon-m-ticket')
                            ->schema([
                                Repeater::make('tickets')
                                    ->disableLabel()
                                    ->label('Entrades')
                                    ->relationship('tickets')
                                    ->collapsed()
                                    ->itemLabel(fn(array $state): ?string => $state['day'] ? ($state['day'] . ' - ' . $state['hour'] . ' (' . $state["tickets"] . ' entrades)' ?? null) : '')
                                    ->schema([
                                        DatePicker::make('day')->label('Dia')->required(),
                                        TimePicker::make('hour')->label('Hora')->required()->format('H:i'),
                                        TextInput::make('tickets')
                                            ->label('Entrades')
                                            ->default(function ($record) use ($venue) {
                                                return $venue ?? 0 ? count($venue->seats) : 0;
                                            })
                                            ->readOnly(!!$venue),
                                        Select::make('language')->label('Idioma')->options([
                                            'ca' => 'Català',
                                            'es' => 'Castellà'
                                        ]),
                                        Forms\Components\Hidden::make('seats')->default(function () use ($venue) {
                                            return $venue->seats;
                                        })->hidden(!$venue),
                                        Placeholder::make('sold')
                                            ->label('Venudes')
                                            ->hidden(fn($record) => !$record)
                                            ->content(fn($record): string => $record ? $record->bookings() : ''),

                                    ])->columns(6),
                                Actions::make([
                                    Action::make('Crea múltiples entrades')
                                        ->form([
                                            Section::make()->schema([
                                                DatePicker::make('date_start')->label('Data d\'inici')->required(),
                                                DatePicker::make('date_end')->label('Data de fi')->required(),
                                            ])->columns(2),
                                            Fieldset::make('Dies de la setmana')->schema([
                                                CheckboxList::make('weekdays')
                                                    ->hiddenLabel()
                                                    ->options([
                                                        1 => 'Dilluns',
                                                        2 => 'Dimarts',
                                                        3 => 'Dimecres',
                                                        4 => 'Dijous',
                                                        5 => 'Divendres',
                                                        6 => 'Dissabte',
                                                        0 => 'Diumenge',
                                                    ])->columns(4)
                                            ])->columns(1),
                                            Section::make()->schema([
                                                TimePicker::make('hour')->label('Hora')->required(),
                                                TextInput::make('tickets')->label('Entrades per sessió')->numeric()->required(),
                                                Select::make('language')->label('Idioma')->options([
                                                    'ca' => 'Català',
                                                    'es' => 'Castellà'
                                                ]),
                                            ])->columns(3),
                                            Toggle::make('delete')->label('Elimina totes les entrades creades prèviament per aquest producte'),
                                        ])
                                        ->action(function (array $data) use ($form, $id, $venue): void {
                                            $product = Product::findOrFail($id);
                                            $w = [0, 1, 2, 3, 4, 5, 6];
                                            if ($data["weekdays"]) {
                                                $w = $data["weekdays"];
                                            }
                                            if ($data["delete"] == 1) {
                                                Ticket::where('product_id', $id)->delete();
                                            }
                                            $start = new \DateTime($data["date_start"]);
                                            $end = $data["date_end"] ?
                                                new \DateTime($data["date_end"]) : $start;
                                            $end->modify('+1 day');

                                            $interval = new \DateInterval('P1D');
                                            $period = new \DatePeriod($start, $interval, $end);
                                            $hour = \Carbon\Carbon::createFromFormat('H:i:s', $data["hour"])->toTimeString();

                                            foreach ($period as $dt) {
                                                $day = $dt->format("Y-m-d");
                                                $dayw = $dt->format('w');
                                                $ticket = Ticket::where("product_id", $id)
                                                    ->where("day", $day)->where("hour", request()->input('hour'))->first();
                                                if ($venue || in_array($dayw, $w)) {
                                                    if (!$ticket) {
                                                        $ticket = new Ticket;
                                                        $ticket->product_id = $id;
                                                        $ticket->day = $day;
                                                        $ticket->hour = $hour;
                                                        if (!$venue) {
                                                            $ticket->language = $data["language"];
                                                            $ticket->tickets = $data["tickets"];
                                                        } else {
                                                            $ticket->seats = $venue->seats;
                                                            $ticket->tickets = count(json_decode($venue->seats));
                                                        }
                                                        $ticket->save();
                                                    }
                                                }
                                            }
                                        })
                                        ->slideOver()
                                ]),
                            ]),
                        Tabs\Tab::make('Preus')
                            ->icon('heroicon-m-currency-euro')
                            ->schema([
                                Repeater::make('productRates')
                                    ->disableLabel()
                                    ->label('Preus')
                                    ->relationship()
                                    ->collapsed()
                                    ->itemLabel(fn(array $state): ?string => $state['rate_id'] ? (Rate::find($state['rate_id'])->title . ' - ' . $state['price'] . ' €' ?? null) : '')
                                    ->schema([
                                        Select::make('rate_id')
                                            ->label('Tarifa')
                                            ->relationship('rate', 'title')
                                            ->searchable()
                                            ->required()
                                            ->columnSpan(2),
                                        TextInput::make('price')
                                            ->label('Preu')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix('€'),
                                    ])->columns(3)
                            ])
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Títol')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('organizer.username')->label('Organitzador'),
                Tables\Columns\TextColumn::make('category.title')->label('Categoria'),
                Tables\Columns\TextColumn::make('bookings_count')->counts('bookings')->badge()->sortable()
                    ->badge()->label('Entrades venudes'),
                Tables\Columns\ToggleColumn::make('active')->label('Actiu'),
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
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->orderBy('order', 'ASC'))
            ->reorderable('order');
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
