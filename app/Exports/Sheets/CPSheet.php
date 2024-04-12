<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use DB;

class CPSheet implements FromView, WithTitle
{
 
    public function view(): View
    {

        $codis_postals_quantitat = DB::table('orders')
            ->select('cp', DB::raw('COUNT(*) as quantitat'))
            ->groupBy('cp')
            ->orderBy('quantitat')
            ->get();

        foreach ($codis_postals_quantitat as $codi_postal) {
            $ciutat_prov = DB::table('codipostal')
                ->select('poblacio', 'provincia')
                ->where('cp', $codi_postal->cp)
                ->first();
            $codi_postal->poblacio = $ciutat_prov->poblacio ?? null;
            $codi_postal->provincia = $ciutat_prov->provincia ?? null;
        }

        $codis_postals_quantitat = $codis_postals_quantitat->toArray();

        return view('admin.excel.cp',[
            'cp' => $codis_postals_quantitat
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Codis postals';
    }
}