<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Rate;
use App\Models\Order;
use App\Models\Product;
use DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;
use URL;
use Redirect;
use Auth;
use Shanmuga\LaravelEntrust\Facades\LaravelEntrustFacade as Entrust;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CartController extends BaseController
{


	protected $layout = 'cistell';


	/**
	 * Cart user page
	 */
	public function show(): View
	{

		return view(
			'cistell',
			array(
				'cistell' => Cart::instance('shopping')->content()
			)
		);

	}

	public function apiCart()
	{
		return response()->json([
			'items' => session('cart') ?? [],
			'total' => Cart::instance('shopping')->total()
		]);
	}

	/**
	 * Convert single products into packs
	 */
	public function convertToPack(Product $product): void
	{

		$packs = $product->packs;

		foreach ($packs as $pack) {

			$keys = $pack->packProducts->modelKeys();
			$rates = $pack->rates;

			if (!$rates)
				continue;

			foreach ($rates as $rate) {

				$preu = $rate->pivot->preu;
				$rows = Cart::instance('shopping')->search(function ($k, $v) use ($rate) {
					return $k->options->id_Rate == $rate->id;
				});

				$prods = array();
				$keysCart = array();

				if ($rows) {

					foreach ($rows as $row) {

						$rowid = $row->rowId;

						if (in_array($row->id, $keys) && !in_array($row->id, $keysCart)) {

							$prods['row'][] = $rowid;
							$prods['qty'][] = $row->qty;
							$prods['reserves'][] = array(
								'product' => $row->id,
								'titol' => $row->model->title,
								'localitat' => $row->options->seat,
								'day' => $row->options->dia,
								'hour' => $row->options->hora
							);
							$keysCart[] = $row->id;

						}

					}

					if (isset($prods["row"]) && (count($prods['row']) == count($keys))) {

						$min_entrades = min($prods['qty']);

						Cart::instance('shopping')->add(
							$pack->id,
							$pack->title,
							$min_entrades,
							$preu,
							0,
							array(
								'name' => $pack->name,
								'parent' => 0,
								'rate' => $rate,
								'target' => $pack->target,
								'reserves' => $prods['reserves']
							)
						)->associate('App\Models\Product');

						for ($i = 0; $i < count($prods['row']); $i++) {
							$qty = $prods['qty'][$i] - $min_entrades;
							if ($qty > 0) {
								Cart::instance('shopping')->update($prods['row'][$i], $qty);
							} else {
								Cart::instance('shopping')->remove($prods['row'][$i]);
							}

						}

					}

				}

			}

		}
	}


	/**
	 * Add standard item
	 */
	public function add(): JsonResponse
	{

		$seats = request()->input('seats');
		if ($seats) {
			$this->addEvent($seats);
		}

		$product_id = request()->input('product_id');
		$product = Product::findOrFail($product_id);

		$day = request()->input('day');
		$hour = request()->input('hour');
		$rates = request()->input('rates');
		$qtys = request()->input('qty');

		if (!$qtys) {
			return redirect()->back();
		}

		// Comprovar mímim d'entrades per a totes les tarifes
		$totalqtys = array_sum($qtys);
		if ($totalqtys < $product->min_tickets) {
			return response()->json([
				'error' => trans('textos.minimEntrades') . $product->min_tickets
			], 405);
		}

		// Màxim d'entrades
		$alCistell = Cart::search(function ($k, $v) use ($product_id, $day, $hour) {
			return $k->id == $product_id && $k->options->dia == $day && $k->options->hora == $hour;
		});
		$qtyCistell = 0;
		foreach ($alCistell as $prod) {
			$qtyCistell += $prod->qty;
		}
		if ($totalqtys + $qtyCistell > $product->max_tickets) {
			return response()->json([
				'error' => trans('textos.max_tickets', ['max' => $product->max_tickets])
			], 405);
		}

		foreach ($qtys as $i => $qty) {

			if ($qty <= 0)
				continue;

			$rate_id = $rates[$i] ?? null;
			if (!$rate_id)
				continue;

			$rate = Rate::findOrFail($rate_id);

			$price = DB::table('product_rate')
				->where('product_id', $product_id)
				->where('rate_id', $rate_id)
				->pluck('price')[0];

			if (Session::has('coupon.p' . $product_id . '_t' . $rate_id)) {
				$price *= 1 - Session::get('coupon.discount') / 100;
			}

			try {

				Cart::instance('shopping')->add(
					$product->id,
					$product->title,
					$qty,
					$price,
					0,
					[
						'name' => $product->name,
						'product_id' => $product->id,
						'day' => $day,
						'hour' => $hour,
						'rate' => $rate->title,
						'rate_id' => $rate_id,
						'image' => $product->image,
					]
				)->associate('\App\Models\Product');

			} catch (\Exception $e) {
				return response()->json([
					'error' => 'Hi ha hagut un error a l\'afegir el producte al cistell'
				], 500);
			}

		}


		$this->convertToPack($product);

		// $quantitats = array_combine($rates, $qtys);
		// Session::put('qty', $quantitats);

		// return Inertia::render('Products/Index', [
		//     'cart' => Cart::content(),
		// ]);

		return response()->json([
			'items' => Cart::instance('shopping')->content(),
			'total' => Cart::instance('shopping')->total()
		]);

	}


	/**
	 * Add product with seats
	 */
	public function addEvent(array $seats): JsonResponse
	{

		$product_id = request()->input('product_id');
		$product = Product::findOrFail($product_id);

		$day = request()->input('day');
		$hour = request()->input('hour');

		$prices = [];
		foreach ($product->rates as $rate) {
			$preus_t = DB::table('product_rate')
				->where('product_id', $product->id)
				->where('rate_id', $rate->id)
				->pluck('pricezone');
			$preus_t = explode(',', $preus_t[0]);
			$prices[$rate->id] = $preus_t;
		}

		foreach ($seats as $seat) {

			$rate_id = $seat['r'];

			$price = $prices[$rate_id][$seat['z'] - 1];
			$rate = Rate::find($rate_id);

			if (Session::has('coupon.p' . $product->id . '_t' . $rate_id)) {
				$price *= 1 - Session::get('coupon.discount') / 100;
			}

			Cart::instance('shopping')->add(
				$product->id,
				$product->title,
				1,
				$price,
				0,
				[
					'name' => $product->name,
					'product_id' => $product->id,
					'day' => $day,
					'hour' => $hour,
					'rate' => $rate->title,
					'rate_id' => $rate_id,
					'seat' => ['s' => $seat['s'], 'f' => $seat['f']]
				]
			)->associate('\App\Models\Product');

		}

		$this->convertToPack($product);

		return response()->json([
			'items' => Cart::instance('shopping')->content(),
			'total' => Cart::instance('shopping')->total()
		]);

	}


	/**
	 * Add pack to cart
	 */
	public function addPack(): RedirectResponse
	{

		// Comprovem si el producte és realment un pack
		$pack = Product::find(request()->input('id_pack'));
		if ($pack) {

			// Info del pack que estem reservant guardada a la sessió
			$sessio = Session::get('pack' . $pack->id);

			$qtys = $sessio['qtys'];
			$rates = $sessio['tarifes'];

			$bookings = $sessio['reserves'];

			$i = 0;

			// Per cada una de les quantitats/tarifes
			foreach ($qtys as $qty) {

				// Si s'ha definit una quantitat vàlida
				if ($qty > 0) {

					// Model Rate
					$rate_id = $rates[$i];
					$rate = Rate::find($rate_id);
					$preu = $rate->producte()->where('productes.id', '=', $pack->id)->first()->pivot->preu;

					//$preu = Rate::with('product')->get()->find($rate_id)->producte->find($product->id)->pivot->preu;
					if (Session::has('codi.p' . $pack->id . '_t' . $rate_id)) {
						$preu = $preu * (1 - Session::get('codi.descompte') / 100);
					}

					// Afegir al cistell
					$cartItem = Cart::add(
						$pack->id,
						$pack->title,
						$qty,
						$preu,
						0,
						array(
							'name' => $pack->name,
							'id_producte' => $pack->id,
							'parent' => 0,
							'rate' => $rate,
							'id_Rate' => $rate->id,
							'target' => $pack->target,
							'reserves' => $bookings
						)
					)->associate('\App\Models\Product');

				}

				$i++;

			}

			Session::forget('pack' . $pack->id);

		}

		return redirect()->route('cistell');

	}


	public function confirm()
	{

		$order = Order::where('session', Session::getId())->where('paid', 0)->where('payment', 'targeta')->orderBy('created_at', 'desc')->first();
		if ($order) {
			return abort(404);
		}
		if (!Cart::instance('shopping')->count()) {
			return redirect()->route('home');
		}
		$lastOrder = false;
		if (auth()->check()) {
			if (!(Entrust::hasRole('admin') || Entrust::hasRole('organizer'))) {
				$lastOrder = Auth::user()->comandes->last();
			} else {
				$lastOrder = (object) [
					'name' => Auth::user()->username,
					'email' => Auth::user()->email
				];
			}
		}
		return Inertia::render('Checkout', [
			'lastOrder' => $lastOrder,
		]);
	}


	/**
	 * Remove item from cart
	 */
	public function removeRow(): JsonResponse
	{
		$rowid = request()->input('rowId');
		$cartitem = Cart::instance('shopping')->content()->where('rowId', $rowid);
		if ($cartitem->isNotEmpty()) {
			Cart::instance('shopping')->remove($rowid);
		}

		return response()->json([
			'items' => Cart::instance('shopping')->content(),
			'total' => Cart::instance('shopping')->total()
		]);
	}


	/**
	 * Update cart row (not using)
	 */
	public function updateItem($rowId): RedirectResponse
	{
		$cartitem = Cart::instance('shopping')->content()->where('rowId', $rowId);
		if ($cartitem->isNotEmpty()) {
			Cart::instance('shopping')->remove($rowId);
		}
		return redirect()->route('cistell');
	}


	/**
	 * Delete all cart rows
	 */
	public function destroy(): JsonResponse
	{
		Cart::instance('shopping')->destroy();
		// Session::forget('coupon');
		//Session::forget('qty');
		return response()->json([
			'items' => Cart::instance('shopping')->content(),
			'total' => Cart::instance('shopping')->total(),
			'message' => 'Cistell buidat'
		]);

	}

}