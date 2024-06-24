<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Ticket;
use Gloudemans\Shoppingcart\Facades\Cart;
use View;
use Session;
use PDF;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;

class ProductController extends BaseController
{


	/**
	 * Home with all products by category
	 */
	public function home()
	{

		$activities = Category::whereHas('products', function ($q) {
			$q->ofTarget('individual');
		})->with('products')->orderBy('order', 'asc')->get();

		$events = Category::whereHas('products', function ($q) {
			$q->ofTarget('esdeveniments');
		})->with('products')->orderBy('order', 'asc')->get();

		$altres = Category::whereHas('products', function ($q) {
			$q->ofTarget('altres');
		})->with('products')->orderBy('order', 'asc')->get();

		$products = array(
			'activities' => $activities,
			'events' => $events,
			'others' => $altres
		);

		return Inertia::render('Home', [
			'products' => fn() => $products,
			'featured' => fn() => Product::select(['title', 'name', 'summary', 'image'])->whereIn('id', [560, 561])->get()
		]);

	}


	/**
	 * Product single page
	 */
	public function show($name, $day = NULL, $hour = NULL)
	{

		$product = Product::withoutGlobalScopes()->where('name', $name)->firstOrFail();
		if (!auth()->user() || auth()->user()->hasRole('organizer') && auth()->user()->id != $product->user_id) {
			if (!$product || !$product->active)
				abort(404);
		}

		$availableDays = $product->availableDays();

		if ($product->is_pack) {
			return Inertia::render('Pack', [
				'product' => $product,
				'availableDays' => $availableDays,
				'tickets' => $product->tickets,
				'day' => $day,
				'hour' => $hour
			]);
		}

		// if (!$day && !$hour && $availableDays->count() == 1) {
		// 	return redirect()->route('product', [
		// 		'name' => $product->name,
		// 		'day' => $availableDays[0]->format('Y-m-d'),
		// 		'hour' => null,
		// 	]);
		// }

		return Inertia::render('Product', [
			'product' => $product,
			'availableDays' => $availableDays,
			'tickets' => fn() => $product->tickets,
			'rates' => fn() => $product->rates,
			'day' => fn() => $day,
			'hour' => fn() => $hour
		]);

		// Is a pack
		// $pack = null;
		// foreach ($product->packs as $item) {
		//     if (Session::has('pack'.$item->id))
		//     {
		//     	$pack = $item;
		//     	break;
		//     }
		// }

		// Producte individual
		if (!$product->is_pack) {

			$availableDaysArray = $product->availableDays();
			$availableDays = [];
			foreach ($availableDaysArray as $d) {
				$availableDays[] = ['date' => $d->format('Y-m-d')];
			}

			$availableDays = json_encode($availableDays);

			// Ítems del producte al cistell
			$subcistell = Cart::search(function ($key, $value) use ($product) {
				return $key->id == $product->id;
			});


			// Redirigir a única funció disponible
			if (!$day && !$hour && $entrades->count() == 1) {
				return redirect()->route('product', [
					'name' => $product->name,
					'dia' => $entrades[0]->dia->format('Y-m-d'),
					'hora' => $entrades[0]->hora->format('H:i:s')
				]);
			}

			$avui = Carbon::now()->toDateString();

			// Passar variables
			View::composer('product', function ($view) use ($product, $subcistell, $pack, $availableDays) {
				$view->with('availableDays', $availableDays);
				$view->with('product', $product);
				$view->with('subcistell', $subcistell);
				$view->with('pack', $pack);
			});


			// El dia està definit
			if ($day) {


				// Bloquejar els dies anteriors
				if ($day < $avui) {
					return view('product')->with('message', 'No hi ha entrades disponibles per aquest producte.');
				}


				// Suggeriments
				//$cistellDia = Cart::search(array('options' => array('dia' => $day)));
				$cistellDia = Cart::search(function ($k, $v) use ($day) {
					return $k->options->dia == $day;
				});

				// Si hi ha ítems al cistell per aquest dia
				if (!empty($cistellDia)) {

					$intervals = array();

					// Troba els intervals d'hores de cada producte al cistell
					foreach ($cistellDia as $prod) {

						$horaC = Carbon::createFromFormat('H:i:s', $prod->options->hora);
						$horaMin = $horaC->subMinutes(119)->toTimeString();
						$horaMax = $horaC->addMinutes(238)->toTimeString();
						$intervals[] = array($horaMin, $horaMax);
						$idprods[] = $prod->id;

					}

					// Afegeix al conjunt de productes el producte actual
					$idprods[] = $product->id;
					// Elimina els repetits
					$idprods = array_unique($idprods);

					// Troba els suggerits del target actual amb les entrades, tenint en compte que no estan dins de l'array de productes del cistell ni dels intevals de cada sessió reservada.
					$suggerits = Product::whereHas('tickets', function ($q) use ($day, $intervals) {
						$q->where('dia', $day);
						for ($i = 0; $i < count($intervals); $i++) {
							$q->whereNotBetween('hora', $intervals[$i]);
						}
					})
						->whereNotIn('id', $idprods)
						->get();



					// Si no hi ha res al cistell, no suggerir res.
				} else {
					$suggerits = NULL;
				}


				// Entrades pel dia escollit
				$sessions = $product->entradesDia($day);

				// Es passa l'hora
				if ($hour) {

					if (strlen($hour) == 2) {
						$hora = $hour . ':00:00';
						return redirect()->route('product', [
							'name' => $product->name,
							'dia' => $day,
							'hora' => $hora
						]);
					} else {
						$hora = substr($hour, 0, 8);
					}

					$entrades = $product->entradesDia($day, $hora);
					if (!$entrades) {
						return view('product', [
							'product' => $name,
							'dia' => $day,
							'error' => 'No hi ha cap sessió programada per aquest dia/hora'
						]);
					}

					$minuts = 60 * $product->limitHores;
					$ara = Carbon::now()->addMinutes($minuts);
					$horasessio = Carbon::parse($day . ' ' . $hora);

					if ($ara > $horasessio) {

						if ($product->limitHores) {
							$missatgeError = 'Ho sentim, la venda d\'entrades online per a <strong>' . $product->title . '</strong> es tanca ' . $product->limitHores . ' hores abans de l\'activitat. Si us plau, tria una altra sessió.';
						} else {
							$missatgeError = 'Ja no es poden comprar entrades per aquesta hora. Tria una altra sessió.';
						}

						if (request()->ajax()) {
							return view(
								'passos.error',
								array(
									'product' => $product,
									'error' => $missatgeError
								)
							);
						}
						return view('product', [
							'product' => $name,
							'dia' => $day,
							'hora' => $hora,
							'error' => $missatgeError
						]);

					}


					// Carregar només part PREU
					if (request()->ajax()) {
						return view(
							'passos.preu',
							array(
								'product' => $product,
								'pack' => $pack,
								'dia' => $day,
								'hora' => $hora,
								'sessions' => $sessions,
								'entrades' => $entrades,
								'suggerits' => $suggerits
							)
						);
					}

					return view(
						'product',
						array(
							'dia' => $day,
							'hora' => $hora,
							'sessions' => $sessions,
							'entrades' => $entrades,
							'suggerits' => $suggerits
						)
					);

				}

				// Només s'ha passat el dia
				if (request()->ajax()) {
					return view(
						'passos.hora',
						array(
							'product' => $product,
							'dia' => $day,
							'sessions' => $sessions,
							'suggerits' => $suggerits
						)
					);
				}
				return view(
					'product',
					array(
						'dia' => $day,
						'sessions' => $sessions,
						'suggerits' => $suggerits
					)
				);

			}

			// Portada del producte
			return view('product');



		}


		// PACK
		else {

			// Mirar si ja s'ha activat la reserva del pack
			if (Session::has('pack' . $product->id)) {
				$products = $product->productesDelPack;
				foreach ($products as $p) {
					if (!Session::has('pack' . $product->id . '.reserves.' . $p->id . '.dia')) {
						return redirect()->action(
							'ProductController@show',
							['name' => $p->name]
						);
					}
				}
			}


			return view(
				'pack',
				array(
					'pack' => $product
				)
			);

		}

	}

	public function image($path)
	{
		$cacheKey = 'image_' . md5($path);
		$cachedImage = Cache::remember($cacheKey, 1, function () use ($path) {
			if (Storage::disk('local')->exists($path)) {
				$directory = explode('/', $path);
				$width = $directory[0] == 'thumbnails' ? 600 : 1400;
				$file = Storage::disk('local')->get($path);
				$image = Image::read($file);
				$image->scale($width, null);
				return $image->encode(new WebpEncoder(quality: 80));
			}
			return false;
		});
		if (!$cachedImage) {
			abort(404);
		}
		return response()->make($cachedImage, 200, ['Content-Type' => 'image/webp']);
	}

	// Dia triat
	public function showDay($name, $day)
	{

		$product = Product::with(
			array(
				'entrades' => function ($query) use ($day) {
					$query->where('dia', $day);
				}
			)
		)->where('name', $name)->first();

		return $product;

	}


	// public function solicitudStore()
	// {
	// 	request()->validate([
	// 		'title' => 'required|max:255'
	// 	]);
	// 	$info = request()->except('image');
	// 	$info['name'] = \App\Helpers\Common::slugify(request()->input('title'));
	// 	$info['actiu'] = 0;
	// 	Product::create($info);
	// 	return redirect()->action('ProductController@solicitud')->with('message', 'S\'ha enviat la sol·licitud');
	// }

	public function search()
	{
		$keyword = request()->input('s');
		$products = Product::where('title', 'like', '%' . $keyword . '%')->orWhere('description', 'like', '%' . $keyword . '%')->get();
		return Inertia::render('Search', [
			'products' => $products,
			'keyword' => $keyword
		]);

	}


	public function calendar()
	{
		$today = strtotime("today");
		$nextMonth = date("Y-m-d", strtotime("+2 month", $today));
		$tickets = Ticket::with('product:id,title,summary,place,name,target')->where('day', '>=', $today)->where('day', '<', $nextMonth)->get();
		$cal = [];
		foreach ($tickets as $item):
			if (!$item->product) {
				continue;
			}
			$cal[] = [
				'uid' => uniqid(),
				'title' => $item->product->title,
				'description' => $item->product->summary,
				'start' => $item->day->format('Y-m-d') . ' ' . $item->hour->format('H:i:s'),
				'location' => $item->product->place,
				'url' => route('product', ['name' => $item->product->name, $item->day->format('Y-m-d'), $item->hour->format('H:i')]),
				'color' => $item->product->target == 'individual' ? 'blue' : 'red'
			];
		endforeach;

		return Inertia::render('Calendar', [
			'events' => $cal,
		]);

	}

	public function ics()
	{

		$tickets = Ticket::with('product:id,title,summary,place,name,target')->where('day', '>=', date('Y-m-d'))->get();
		$events = [];
		foreach ($tickets as $item):

			$event = Event::create($item->producte->title)
				->startsAt(new \DateTime($item->dia->format('Y-m-d') . ' ' . $item->hora->format('H:i:s')))
				->description($item->producte->resum_ca);
			$events[] = $event;

		endforeach;

		$calendar = Calendar::create(config('app.name'))
			->event($events)
			->get();

		return $calendar;
	}


	public function previewPdf($id)
	{
		$product = Product::find($id);
		$pdf = PDF::setOptions(['isRemoteEnabled' => true])->loadView(
			'pdf.contracte-preview',
			array(
				'product' => $product
			)
		);
		return $pdf->stream('preview-' . $id . '.pdf');
	}


}