<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Models\Vals\Qr;
use App\Models\Vals\Comerc;
use App\Models\Vals\Usuari;
use Mail;
use App\Mail\Vals\ValNou;
use PDF;
use Excel;
use App\Exports\ValsExport;
use App\Exports\ValsQrExport;
use Illuminate\View\View;

class ValController extends BaseController {

    public $codis_inici;
    public $codis_fi;
    public $qr_inici;
    public $qr_fi;

    public function __construct() {
        $this->codis_inici = DB::table('options')
        ->select('value')
        ->where('key', 'vals_codis_inici')
        ->value('value') ?? null;
        $this->codis_fi = DB::table('options')
        ->select('value')
        ->where('key', 'vals_codis_fi')
        ->value('value') ?? null;
        $this->qr_inici = DB::table('options')
        ->select('value')
        ->where('key', 'vals_qr_inici')
        ->value('value') ?? null;
        $this->qr_fi = DB::table('options')
        ->select('value')
        ->where('key', 'vals_qr_fi')
        ->value('value') ?? null;
        view()->share('codis_inici',$this->codis_inici);
        view()->share('codis_fi',$this->codis_fi);
        view()->share('qr_inici',$this->qr_inici);
        view()->share('qr_fi',$this->qr_fi);
    }


    // Portada vals
    public function create(): View
    {
        $comercos = Comerc::where('actiu',1)->whereNotNull('sector')->get();
        $sectors = $comercos->sortBy('sector')->unique('sector')->pluck('sector');
        return view('vals.index',[
            'comercos'=>$comercos,
            'sectors'=>$sectors,
            //'open'=>env('APP_ENV')=='local'||date('Y-m-d')>='2021-12-01'||auth()->check()
            'open' => true
        ]);
    }

    /**
     * Checks if code is valid
     */
    public function checkCode()
    {

        if (!request()->query('test')) {
            // Too soon
            if( date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime($this->codis_inici)) ) {
                return redirect()->back()->with('error_soon',true);
            }
            // Too late
            if ( date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($this->codis_fi)) ) {
                return redirect()->back()->with('error_late',true);
            }
        }

        // El codi existeix
        $codi = str_replace(' ', '', strtoupper(request()->input('codi')));
        $querycodi = DB::table('vals_codis')->where('codi',$codi)->first();
        if (!$querycodi) {
            return redirect()->back()->with('error_codi','El codi que has entrat no és vàlid.');
        }
        // S'ha fet servir el codi
        $usuari = Usuari::where('codi_butlleta',$codi)->first();
        if($usuari) {
            return redirect()->back()->with('error_codi','El codi que has entrat ja s\'ha fet servir. Si tens qualsevol dubte, pots trucar o enviar un WhatsApp al 690 87 26 94.');
        }

        // Premi
        if ($querycodi->premi) {
            return redirect()->back()->with([
                'success' => true,
                'code' => $querycodi
            ]);
        }

        // No premi
        return redirect()->back()->with([
            'code' => $querycodi,
            'fail' => true
        ]);

    }

    public function store()
    {
        $validator = request()->validate([
			'dni' => 'required',
			'name' => 'required',
			'cognoms' => 'required',
            'localitat' => 'required',
            'email' => 'required|email|confirmed',
            'acceptation' =>'accepted',
		]);

        // DNI sense espais ni guions i amb majúscula
        $dni = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", request()->input('dni')));

        // El DNI és al padró
        $querydni = DB::table('vals_dni')->where('dni',$dni)->first();

        // El codi existeix
        // $codi = str_replace(' ', '', strtoupper(request()->input('codi')));
        // $querycodi = DB::table('vals_codis')->where('codi',$codi)->first();

        // if (!$querycodi) {
        //     return redirect()->back()->with('error_codi','El codi que has entrat no és vàlid.');
        // }

        // S'ha fet servir el codi
        // $usuari = Usuari::where('codi_butlleta',$codi)->first();

        // if($usuari) {
        //     return redirect()->back()->with('error_codi','El codi que has entrat ja s\'ha fet servir. Si tens qualsevol dubte, pots trucar o enviar un WhatsApp al 690 87 26 94.');
        // }

        $querycodi = DB::table('vals_codis')->where('codi',request()->input('codi'))->first();

        $usuari = new Usuari([
            'email' => request()->input('email'),
            'name' => request()->input('name'),
            'cognoms' => request()->input('cognoms'),
            'localitat' => request()->input('localitat'),
            'telefon' => request()->input('telefon'),
            'registre' => request()->input('registre'),
            'solsona' => $querydni?1:0,
            'dni' => $dni,
            'codi' => substr(md5(openssl_random_pseudo_bytes(20)),-6),
            'codi_butlleta' => request()->input('codi'),
            'premi' => $querycodi->premi
        ]);
        $usuari->save();

        // No hi ha premi :(
        // if(!$usuari->premi) {
        //     // No pot optar a sorteig :(
        //     if(!$usuari->solsona) {
        //         return redirect()->back()->with([
        //             'fail' => true
        //         ]);
        //     }
        //     // Pot optar a sorteig!
        //     return redirect()->back()->with([
        //         'fail_poll' => true
        //     ]);
        // }

        // Codi amb premi
        if($querycodi->premi) {
            $this->sendMailVals($usuari);
            return redirect()->back()->with([
                'email' => request()->input('email')
            ]);
        }
        return redirect()->back();

    }

    public function storeAnonymous()
    {
        $usuari = new Usuari([
            'codi' => substr(md5(openssl_random_pseudo_bytes(20)),-6),
            'codi_butlleta' => '00000000',
            'premi' => request()->input('quant')
        ]);
        $usuari->save();
        $usuari->codi_butlleta = 'SOLCAR'.$usuari->id;
        $usuari->save();
        return redirect()->back()->with([
            'message' => "Vals creats correctament. <a href='".route('vals.pdf',['codi'=>$usuari->codi])."'>Descarrega PDF</a>"
        ]);
    }


    // Comprovar DNI's, crear vals
    /*
    public function store() 
    {

        $validator = request()->validate([
			'dni' => 'required',
			'name' => 'required',
			'cognoms' => 'required',
            'email' => 'required|email|confirmed',
            'acceptation' =>'accepted',
		]);

        // DNI sense espais ni guions i amb majúscula
        $dni = strtoupper(preg_replace("/[^a-zA-Z0-9]/", "", request()->input('dni')));

        // El DNI és al padró
        $query = DB::table('vals_dni')->where('dni',$dni)->get();
        if(count($query)){

            // Es crea el nou qr amb les dades del client
            $usuari = Usuari::where('dni',$dni)->first();

            if ($usuari) 
            {
                if($usuari->paid) {
                    $this->sendMailVals($usuari);
                    return redirect()->back()->withError('Ja has fet servir aquesta promoció. T\'hem enviat de nou els teus vals a l\'adreça de correu electrònic indicada. Si no els has rebut, contacta a...');
                }
                return redirect()->route('vals.tpv',['qr'=>$usuari]);
            }
            else 
            {
                $usuari = new Usuari([
                    'email' => request()->input('email'),
                    'name' => request()->input('name'),
                    'cognoms' => request()->input('cognoms'),
                    'dni' => $dni,
                    'codi' => substr(md5(openssl_random_pseudo_bytes(20)),-6)
                ]);
                $usuari->save();
                return redirect()->route('vals.tpv',['qr'=>$usuari]);
            }

        } 
        
        // El DNI no existeix al padró.
        else {
            return redirect()->back()->withError('Ho sentim, no pots optar a aquesta promoció.');
        }
    }
    */

    // TPV
    public function tpv($qr_id)
    {
        $usuari = Usuari::findOrFail($qr_id);
        if(!$usuari->paid) {
            return view('vals.tpv',['qr'=>$usuari,'entorn'=>'proves']);
        } else {
            return 'Enllaç caducat';
        }
        
    }

    // Pagament i enviament dels QR
    /*
    public function tpvResponse() 
    {
        try {

			$request = $_POST;
			if(!$_POST) {
				$_POST = $_GET;
			}

			$redsys = new \Sermepa\Tpv\Tpv();

			$key = config('redsys.key');
			$parameters = $redsys->getMerchantParameters($_POST["Ds_MerchantParameters"]);
			$DsResponse = $parameters["Ds_Response"];
			$DsResponse += 0;

            $idqr = substr($parameters["Ds_Order"], 0, -3);
            $idqr = substr($idqr, 2, 7);
            $usuari = Usuari::findOrFail($idqr);

			if ($redsys->check($key, $_POST) && $DsResponse <= 99) {

                // Missatge OK
                $usuari->update(array(
                    'tpv_id' => $parameters["Ds_Order"],
                    'pagat'=>1
                ));

                // Email per l'usuari
                $this->sendMailVals($usuari);

                Log::debug("Compra vals ".$usuari->name." ".$usuari->cognoms);

				return 'OK';

			} else {
				
                Mail::to($usuari->email)->send(new ValPagamentFallit($usuari));
                Log::error("Error de pagament ".$usuari->email);
				return 'NO';

			}

		} catch (Exception $e) {

			echo 'Caught exception: ',  $e->getMessage(), "\n";
			Log::error($e->getMessage());

		}
    }
    */

    public function sendMailVals($usuari)
    {
        Mail::to($usuari->email)->send(new ValNou($usuari));
    }

    public function resendMail($usuari)
    {
        $this->sendMailVals($usuari);
        return redirect()->back();
    }

    // PDF que s'envia als usuaris
    public function pdf($codi)
    {
        $qr = Usuari::where('codi',$codi)->firstOrFail();
        $comercos = Comerc::where('actiu',1)->orderBy('sector')->get();

        $pdf = PDF::getFacadeRoot();
        $dompdf = $pdf->getDomPDF();
        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ],
        ]));

        $pdf->setOptions(['isRemoteEnabled' => true])->loadView('vals.pdf',array(
            'qr'=>$qr,
            'comercos'=>$comercos
        ));
        return $pdf->stream('vals-'.$qr->id.'.pdf');
    }

    // QR escanejat, formulari validació
    public function qr($codi,$count)
    {
        $qr = Usuari::where('codi',$codi)->firstOrFail();
        if($count > $qr->premi) {
            abort(404);
        }
        return view('vals.codi',[
            'qr'=>$qr,
            'count'=>$count
        ]);
    }

    // Validació, correu confirmació, pantalla OK
    public function activate($qr,$count)
    {

        $usuari = Usuari::where('codi',$qr)->firstOrFail();
        $comerc = Comerc::where('clau',request()->input('clau'))->first();

        if(!$comerc) {
            return redirect()->back()->withError('El codi de comerç no és correcte');
        }

        $qr = Qr::where('usuari_id',$usuari->id)->where('comerc_id',$comerc->id)->where('count',$count)->first();

        if($qr) {
            return redirect()->back()->withError('Aquest val ja s\'ha activat');
        }

        $qr = new Qr([
            'usuari_id'=>$usuari->id,
            'comerc_id'=>$comerc->id,
            'count'=>$count
        ]);
        $qr->save();

        /*
        try {
            Mail::to($usuari->email)->send(new ValActivat($qr));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        */
        
        return redirect()->back()->withMessage('Val activat correctament!');
        
    }


    // Textos legals

    public function condicionsUs()
    {
        return view('vals.condicions-us');
    }
    public function proteccioDades()
    {
        return view('vals.proteccio-dades');
    }


    // Admin

    public function index()
	{
		$users = Usuari::paginate(25);
		return view('vals.admin.home',[
            'usuaris'=>$users,
            'menu'=>'vals'
        ]);
	}

    public function storeDates()
    {
        DB::table('options')
            ->where('key', 'vals_codis_inici')
            ->update(['value' => request()->input('vals_codis_inici')]);
        DB::table('options')
            ->where('key', 'vals_codis_fi')
            ->update(['value' => request()->input('vals_codis_fi')]);
        DB::table('options')
            ->where('key', 'vals_qr_inici')
            ->update(['value' => request()->input('vals_qr_inici')]);
        DB::table('options')
            ->where('key', 'vals_qr_fi')
            ->update(['value' => request()->input('vals_qr_fi')]);
        return redirect()->back()->with('message','Dates actualitzades');
    }

    public function indexComercos()
	{
		$comercos = Comerc::orderBy('actiu','desc')->orderBy('name','asc')->get();
		return view('vals.admin.comercos',['comercos'=>$comercos,'menu'=>'comercos']);
	}

    public function editComerc($id)
    {
        $comerc = Comerc::findOrFail($id);
        return view('vals.admin.comercos-edit',['comerc'=>$comerc,'menu'=>'comercos']);
    }

    public function updateComerc($id)
    {
        $comerc = Comerc::findOrFail($id);
        $comerc->update(request()->all());
        return redirect()->route('vals.admin.comercos.index')->withMessage("Editat ".$comerc->name);
    }

    public function storeComerc()
    {
        $clau = false;
        while (!$clau) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randstring = '';
            for ($j = 0; $j < 4; $j++) {
                $randstring .= $characters[rand(0, strlen($characters)-1)];
            }
            $code_exists = DB::table('vals_comercos')->where('clau',$randstring)->first();
            if (!$code_exists) {
                $clau = $randstring;
            }
        }
        $input = request()->all();
        $input['clau'] = $clau;
        $input['actiu'] = 1;
        $comerc = Comerc::create($input);
        return redirect()->back()->withMessage('Nou comerç: '.$comerc->name);
    }

    public function toggleComerc($id)
    {
        $comerc = Comerc::findOrFail($id);
        $comerc->actiu = !$comerc->actiu;
        $comerc->save();
        return redirect()->back()->withMessage("S'ha ".($comerc->actiu?'':'des')."activat el comerç ".$comerc->name);
    }

    public function export()
    {
        return Excel::download(new ValsExport, 'usuaris_vals.xlsx');
    }
    public function exportQr()
    {
        return Excel::download(new ValsQrExport, 'qr_vals.xlsx');
    }

    
    // Accés comerços

    public function comercAdmin()
    {
        if(session('comercadmin')){
            $comerc = Comerc::find(session('comercadmin'));
            return view('vals.comercadmin',['comerc'=>$comerc]);
        } else {
            return view('vals.comercadmin');
        }
        
    }

    public function comercLogin()
    {
        $comerc = Comerc::where('clau',request()->input('clau'))->first();
        if($comerc) {
            session(['comercadmin'=>$comerc->id]);
            return redirect()->back();
        } else {
            return redirect()->back()->withError('La clau no és correcta');
        }
        
    }

    public function comercLogout()
    {
        session()->forget('comercadmin');
        return redirect()->back();
    }

}