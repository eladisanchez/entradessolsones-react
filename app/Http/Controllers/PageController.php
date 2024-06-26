<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

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
    public function conditions(): InertiaResponse
    {
        $conditions = Common::optext('condicions');
        return Inertia::render('Basic', [
            'title' => "Condicions d'ús",
            'content' => $conditions,
        ]);
    }


    /**
     * Privacy policy
     */
    public function privacyPolicy(): InertiaResponse
    {
        $text = Common::optext('politica-privacitat');
        return Inertia::render('Basic', [
            'title' => 'Política de privacitat',
            'content' => $text,
        ]);
    }


    /**
     * Organizator signup form
     */
    public function organitzators(): InertiaResponse
    {
        $text = Common::optext('organitzadors');
        return Inertia::render('Basic', [
            'title' => "Com puc vendre entrades?",
            'content' => $text,
        ]);
    }

}
