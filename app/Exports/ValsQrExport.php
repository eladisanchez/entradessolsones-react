<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Reserva;
use Input;
use Carbon\Carbon;
Use App\Vals\Qr;

class ValsQrExport implements FromCollection, WithMapping, WithHeadings
{

    public function headings(): array
    {
        return [
            'Data ús',
            'Comerç',
            'DNI',
            'Nom',
            'Cognoms',
            'Email',
            'Data compra',
        ];
    }

    public function collection()
    {
        return Qr::orderBy('created_at')->get();
    }

    public function map($qr): array
    {
        return [
            $qr->created_at->format('m/d/Y'),
            $qr->comerc->name,
            $qr->usuari->dni,
            $qr->usuari->name,
            $qr->usuari->cognoms,
            $qr->usuari->email,
            $qr->usuari->created_at->format('m/d/Y')
        ];
    }

    

}