<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use App\Models\Booking;
use DB;

class BookingsByTarget implements FromView, WithTitle
{

    public $bookings;

    public function __construct() {
        // Count bookings by product.target
        $this->bookings = Booking::select('products.target', DB::raw('COUNT(*) as quantitat'))
            ->join('products', 'bookings.product_id', '=', 'products.id')
            ->groupBy('products.target')
            ->orderBy('quantitat')
            ->get();
        dd($this->bookings);
    }

    public function view(): View
    { 
        return view('admin.excel.bookings',[
            'bookings' => $this->bookings
        ]);
    }

    public function title(): string
    {
        return 'Reserves segons target';
    }
}