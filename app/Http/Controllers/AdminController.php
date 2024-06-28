<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Exports\AnalyticsExport;
use App\Exports\DataExport;

class AdminController extends BaseController 
{

    public function data(): BinaryFileResponse
    {
        return Excel::download(new DataExport, 'data.xlsx');
    }

}