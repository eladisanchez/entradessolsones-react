<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;
use App\Models\Order;
use App\Models\Ticket;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Productes actius', Product::where('active', 1)->count())
                ->description('Total: ' . Product::count()),
            Stat::make('Entrades a la venda', number_format(Ticket::where('day', '>=', now())->sum('tickets'), 0, ',', '.'))
                ->description('Fins al ' . Ticket::max('day')),
            Stat::make('Comandes', number_format(Order::count(), 0, ',', '.'))
                ->description('Últim mes: '. Order::where('created_at', '>=', now()->subMonth())->count()),
            Stat::make('Total vendes', number_format(Order::where('paid', 1)->sum('total'), 2, ',', '.') . ' €')
                ->description('Últim mes: ' . number_format(Order::where('paid', 1)->where('created_at', '>=', now()->subMonth())->sum('total'), 2, ',', '.') . ' €')
        ];
    }
}
