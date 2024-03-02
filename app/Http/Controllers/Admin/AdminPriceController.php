<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use App\Models\Rate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPriceController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}

	/**
	 * All prices available given a product ID
	 */
	public function index(string $product_id): View
	{
		$product = Product::findOrFail($product_id);
		$prices = $product->rates;

		$tarifes_disponibles = $prices->count() ?
			Rate::whereNotIn('id', $prices->modelKeys())->pluck('title_ca', 'id') :
			Rate::pluck('title_ca', 'id');

		return view('admin.product.prices', [
			'product' => $product,
			'prices' => $prices,
			'tarifes_disponibles' => $tarifes_disponibles
		]);
	}

	/**
	 * Save product price
	 */
	public function store(string $product_id): RedirectResponse
	{
		$product = Product::findOrFail($product_id);

		if ($product->vendor_id) {

			$preu_general = request()->input('preu');
			$preus = request()->input('preu_zona');
			$preusok = array_map(function ($p) use ($preu_general) {
				if (!empty($p)) {
					return $p;
				} else {
					return $preu_general;
				}
			}, $preus);
			$preustring = implode(',', $preusok);
			$product->rates()->attach([
				request()->input('rate_id') => [
					'price' => $preu_general,
					'pricezone' => $preustring
				]
			]);

		}
		// Producte normal
		else {
			$product->rates()->attach([request()->input('rate_id') => ['preu' => request()->input('price')]]);
		}

		return redirect()->back();
	}

	/**
	 * Destroy a price for a product/rate
	 */
	public function destroy(string $product_id, string $rate_id): RedirectResponse
	{
		$product = Product::find($product_id);
		$product->rates()->detach($rate_id);
		return redirect()->route('admin.price.index', $product_id)
			->with('message', 'Preu eliminat.');

	}


}