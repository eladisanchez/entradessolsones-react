<?php

namespace App\Http\Controllers;

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
use Entrust;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends BaseController {


	protected $layout = 'cistell';


	/**
	 * Cart user page
	 */
	public function show(): View
	{

		return view('cistell',array(
			'cistell' => Cart::content()
		));

	}

	/**
	 * Convert single products into packs
	 */
	public function convertToPack(Product $product): void
	{
		
		$packs = $product->packs;

		foreach ($packs as $pack) {

			$keys = $pack->productesDelPack->modelKeys();
			$rates = $pack->rates;

			foreach ($rates as $rate) {

				$preu = $rate->pivot->preu;
				$rows = Cart::search(function($k,$v) use ($rate) {
					return $k->options->id_Rate == $rate->id;
				});

				$prods = array();
				$keysCart = array();

				if ($rows) {

					foreach ($rows as $row) {

						$rowid = $row->rowId;

						if( in_array($row->id,$keys) && !in_array($row->id,$keysCart)) {

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

					if ( isset($prods["row"]) && (count($prods['row']) == count($keys)) ) {

						$min_entrades = min($prods['qty']);
						
						Cart::add($pack->id, $pack->title, $min_entrades, $preu, 0, array(
							'name' => $pack->name, 
							'parent' => 0,
							'rate' => $rate,
							'target' => $pack->target,
							'reserves' => $prods['reserves']
							)
						)->associate('App\Producte');

						for ( $i=0; $i<count($prods['row']); $i++ ) {
							$qty = $prods['qty'][$i] - $min_entrades;
							if ($qty>0) {
								Cart::update($prods['row'][$i], $qty);
							} else {
								Cart::remove($prods['row'][$i]);
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
	public function addItems(): RedirectResponse
	{

		$product_id = request()->input('product');
		$product = Product::findOrFail($product_id);
		
		$day = request()->input('day');
		$hour = request()->input('hour');
		$tarifes = request()->input('rate');
		$qtys = request()->input('qty');

		if ($product->parent_id>0) {
			$parent = $product->parent_id;
		} else {
			$parent = $product->id;
		}

		$i=0;

		// No s'han definit quantitats
		if (!$qtys) {
			return redirect()->back();
		}

		// Comprovar mímim d'entrades per a totes les tarifes
		$totalqtys = array_sum($qtys);
		if ($totalqtys < $product->minimEntrades) {
			return redirect()->back()->with(array('message' => trans('textos.minimEntrades').$product->minimEntrades ));
		}

		// Màxim d'entrades
		$alCistell = Cart::search(function($k,$v) use ($product_id,$day,$hour) {
			return $k->id == $product_id && $k->options->dia == $day && $k->options->hora == $hour;
		});
		$qtyCistell = 0;
		foreach($alCistell as $prod) {
			$qtyCistell += $prod->qty;
		}
		if ($totalqtys+$qtyCistell>$product->maximEntrades) {
			return redirect()->back()->with(array('message' => trans('textos.maximEntrades',['max' => $product->maximEntrades ])));
		}


		// Per cada quantitat
		foreach($qtys as $qty) {

			// Si s'ha definit una quantitat vàlida
			if($qty>0) {

				// Model Rate
				$rate_id = $tarifes[$i];
				$rate = Rate::find($rate_id);

				$preu = Rate::with('product')->get()->find($rate_id)->producte->find($product->id)->pivot->preu;
				if ( Session::has('codi.p'.$product->id.'_t'.$rate->id) ) {
					$preu = $preu * (1 - Session::get('codi.descompte')/100 );
				}
				

				Cart::add(
					$product->id, 
					$product->title, 
					$qty, 
					$preu,
					0,
					[
						'name' => $product->name, 
						'id_producte' => $product->id,
						'parent' => $parent,
						'day' => $day, 
						'hour' => $hour, 
						'rate' => $rate,
						'id_rate' => $rate->id,
						'target' => $product->target
					]
				)->associate('App\Producte');

			}

			$i++;

		}


		$this->convertToPack($product);

		$quantitats = array_combine($tarifes, $qtys);
		Session::put('qty', $quantitats);
	

		$url = URL::route('product',[
			'name' => $product->name,
			'day' => $day,
			'hour' => $hour
		]);

		return redirect()->to($url)->with('itemAdded',true);		

	}


	/**
	 * Add product with seats
	 */
	public function addEvent(): RedirectResponse
	{

		$product_id = request()->input('product');
		$product = Product::find($product_id);

		if(!$product) {
			return Redirect::back()->with('message', 'L\'esdeveniment encara no està obert.');
		}

		$day = request()->input('day');
		$hour = request()->input('hour');

		//$rate = Rate::find(request()->input('rate'));

		$preus = [];
		foreach($product->rates as $rate) 
		{
			// Agafar tots els preus de la base de dades
			$preus_t = DB::table('product_rate')
				->where('producte_id', $product->id)
				->where('rate_id', $rate->id)
				->pluck('preuzona');
			$preus_t = explode(',',$preus_t[0]);
			$preus[$rate->id] = $preus_t;
		}
		

		if (!request()->has('localitats')) {
			return Redirect::back()->with('message', 'Selecciona els seients per afegir-los al cistell.');
		}
		$localitats = json_decode(request()->input('localitats'));

		// Per cada localitat
		foreach($localitats as $loc) {

			$preu = $preus[$loc->t][$loc->z-1];
			$rate = Rate::find($loc->t);

			//$preu = Rate::with('product')->get()->find($loc->t)->producte->find($product->id)->pivot->preu;
			if ( Session::has('codi.p'.$product->id.'_t'.$loc->t) ) {
				$preu = $preu * (1 - Session::get('codi.descompte')/100 );
			}

			$rate_id = $loc->t;
			unset($loc->t);

			// Afegir al cistell
			$cartItem = Cart::add($product->id, $product->title, 1, $preu, 0, array(
				'name' => $product->name, 
				'id_producte' => $product->id,
				'parent' => $product->id,
				'day' => $day, 
				'hour' => $hour, 
				'rate' => $rate,
				'id_Rate' => $rate_id,
				'localitat' => $loc,
				'target' => $product->target)
			)->associate('App\Producte');
				

		}

		$this->convertToPack($product);

		return redirect()->back()->with('itemAdded',true);

	}


	/**
	 * Add pack to cart
	 */
	public function addPack(): RedirectResponse
	{

		// Comprovem si el producte és realment un pack
		$pack = Product::find(request()->input('id_pack'));
		if($pack)
		{

			// Info del pack que estem reservant guardada a la sessió
			$sessio = Session::get('pack'.$pack->id);

			$qtys = $sessio['qtys'];
			$tarifes = $sessio['tarifes'];

			$bookings = $sessio['reserves'];

			$i=0;

			// Per cada una de les quantitats/tarifes
			foreach($qtys as $qty) {

				// Si s'ha definit una quantitat vàlida
				if($qty>0) {

					// Model Rate
					$rate_id = $tarifes[$i];
					$rate = Rate::find($rate_id);
					$preu = $rate->producte()->where('productes.id', '=', $pack->id)->first()->pivot->preu;

					//$preu = Rate::with('product')->get()->find($rate_id)->producte->find($product->id)->pivot->preu;
					if ( Session::has('codi.p'.$pack->id.'_t'.$rate_id) ) {
						$preu = $preu * (1 - Session::get('codi.descompte')/100 );
					}

					// Afegir al cistell
					$cartItem = Cart::add($pack->id, $pack->title, $qty, $preu, 0, array(
						'name' => $pack->name, 
						'id_producte' => $pack->id,
						'parent' => 0,
						'rate' => $rate, 
						'id_Rate' => $rate->id,
						'target' => $pack->target,
						'reserves' => $bookings
						)
					)->associate('App\Producte');

				}

				$i++;

			}

			Session::forget('pack'.$pack->id);

		}

		return redirect()->route('cistell');

	}


	public function confirm() 
	{	

		$order = Order::where('sessio',Session::getId())->where('pagat',0)->where('pagament','targeta')->orderBy('created_at', 'desc')->first();
		if($order) {
			return redirect()->route('checkout-tpv-ko',['id'=>$order->id]);
		}
		$last = false;
		if(Auth::check()) {
			if(!(Entrust::hasRole(['admin','entitat']))) {
				$last = Auth::user()->comandes->last();
			} else {
				$last = (object) [
					'name'=>Auth::user()->username,
					'email'=>Auth::user()->email
				];
			}
		}
		return view('confirmacio')->with(compact('last'));
	}


	/**
	 * Remove item from cart
	 */
	public function removeItem(): RedirectResponse
	{
		$rowid = request()->input('rowid');
		$cartitem = Cart::content()->where('rowId',$rowid);
		if($cartitem->isNotEmpty()){
			Cart::remove($rowid);
		}
		return redirect()->back();
	}
	

	/**
	 * Update cart row (not using)
	 */
	public function updateItem($rowId): RedirectResponse
	{
		$cartitem = Cart::content()->where('rowId',$rowId);
		if($cartitem->isNotEmpty()){
			Cart::remove($rowId);
		}
		return redirect()->route('cistell');
	}


	/**
	 * Delete all cart rows
	 */
	public function emptyCart(): RedirectResponse
	{
		Cart::destroy();
		Session::forget('codi');
		Session::forget('qty');
		return redirect()->route('cistell');
	}

}