<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Extract;

class ExtractExport implements FromView, WithTitle, WithHeadings, ShouldAutoSize
{

    public $extract;

    public function __construct(Extract $extract)
    {
        $this->extract = $extract;
    }

    public function headings(): array
    {
        return [
            'Producte',
            'Entrades',
            'Total',
            'Balanç'
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function title(): string
    {
        return 'Extracte ' . $this->extract->date_start->format('d-m-Y');
    }

    public function view(): View
    {

        return view('admin.extractes.excel', ['extract' => $this->extract]);

    }
}