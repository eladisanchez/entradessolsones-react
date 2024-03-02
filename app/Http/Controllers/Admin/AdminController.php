<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Exports\AnalyticsExport;

class AdminController extends BaseController 
{


    public function __construct()
	{
		view()->share([
			'menu' => 'options'
		]);
	}

    /**
     * Admin home
     */
    public function home(): View
    {
        return view('admin.home');
    }


    /**
     * Export analytics excel
     */
    public function excelExport(): BinaryFileResponse
    {
        $date = date('Y-m-d');
        return Excel::download(new AnalyticsExport, 'entrades-solsones-'.$date.'.xlsx');
    }


    /**
     * Options page
     */
    public function indexOptions(): View
    {
        $tot = DB::table('options')->get();
        $values = array();
        foreach($tot as $i) {
            $values[$i->key] = $i->value;
        }
        return view('admin.options')->with(['values'=>$values]);
    }  


    /**
     * Save options
     */
    public function updateOptions(): RedirectResponse
    {
        foreach(request()->input() as $i => $v) {
            DB::table('options')
                ->where('key', $i)
                ->update(['value' => $v]);
        }
        return redirect()->back();
        
    }  


}