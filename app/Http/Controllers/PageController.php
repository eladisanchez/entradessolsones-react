<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        return view('home');
    }


    /**
     * Purchase conditions
     */
    public function conditions(): View
    {
        $condicions = Common::optext('condicions');
		return view('page')->with(['text'=>$condicions,'titol'=>"Condicions d'ús"]);
    }


    /**
     * Privacy policy
     */
    public function privacyPolicy(): View
    {
        $text = Common::optext('politica-privacitat');
		return view('page')->with(['text'=>$text,'titol'=>'Política de privacitat']);
    }


    /**
     * Organizator signup form
     */
    public function organitzators(): View
    {
        $text = Common::optext('organitzadors');
		return view('solicitud-alta')->with([
			'text'=>$text,
			'titol'=>__("Com puc vendre entrades?")]);
    }

}
