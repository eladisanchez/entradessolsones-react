<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class DataExport implements WithMultipleSheets
{

    use Exportable;

    public function sheets(): array
    {
        $sheets = [];
        // $sheets[] = new Sheets\SalesSheet();
        //$sheets[] = new Sheets\CPSheet();
        $sheets[] = new Sheets\BookingsByTarget();

        return $sheets;
    }

}