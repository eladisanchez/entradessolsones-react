<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Booking;
use Input;
use Carbon\Carbon;

class AnalyticsExport implements FromView
{
    public function view(): View
    {

        $bookings = Booking::whereHas('order', function($q){
            //$q->where('created_at', '>=', (new \Carbon\Carbon)->submonths(2));
        })->with('product','rate','order');
        
        // Per dia
        if (request()->input('inici')) {
            //$diainici = Carbon::createFromFormat('Y-m-d',request()->input('inici'));
            $bookings->where('day','>=',request()->input('inici'));
            if (request()->has('fi')) {
                //$diafi = Carbon::createFromFormat('Y-m-d',request()->input('fi'));
                $bookings->where('day','<=',request()->input('fi'));
            }
        }
        
        if (request()->input('product_id')) {
            $bookings->where('product_id',request()->input('product_id'));
        }

        if(request()->input('day')) {
            $bookings->where('day',request()->input('day'));
        }
        

        $bookings = $bookings->get();
        //$bookings = Reserva::with('product','tarifa','comanda')->get();
        //@dd([request()->input('dia'),request()->input('producte_id'),$bookings]);
        
        return view('admin.booking.excel',['bookings'=>$bookings]);
		
    }
}