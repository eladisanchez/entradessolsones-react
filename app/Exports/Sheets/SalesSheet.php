<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use App\Models\Order;

class SalesSheet implements FromView, WithTitle
{

    public $sales;

    public function __construct() {
        $this->sales = Order::withCount('bookings')->withSum('bookings', 'tickets')->get();
    }

    public function view(): View
    { 
        return view('admin.excel.sales',[
            'sales' => $this->sales
        ]);
    }

    public function title(): string
    {
        return 'Vendes';
    }
}