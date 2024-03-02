<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Reserva;
use Input;
use Carbon\Carbon;
Use App\Vals\Usuari;

class ValsExport implements FromCollection, WithMapping, WithHeadings
{

    public function headings(): array
    {
        return [
            'id',
            'DNI',
            'Nom',
            'Cognoms',
            'Email',
            'Telèfon',
            'Localitat',
            'Codi QR',
            'Codi butlleta',
            'Data compra',
            'Núm vals',
            'És de Solsona',
            'Vol rebre info',
            'Opta a sorteig'
        ];
    }

    public function collection()
    {
        return Usuari::all();
    }

    public function map($usuari): array
    {
        // This example will return 3 rows.
        // First row will have 2 column, the next 2 will have 1 column
        return [
            $usuari->id,
            $usuari->dni,
            $usuari->name,
            $usuari->cognoms,
            $usuari->email,
            $usuari->telefon,
            $usuari->seat,
            $usuari->codi,
            $usuari->codi_butlleta,
            $usuari->created_at->format('m/d/Y'),
            $usuari->premi,
            $usuari->solsona,
            $usuari->registre,
            (!$usuari->premi && $usuari->solsona) ? 1 : 0
        ];
    }

    

}