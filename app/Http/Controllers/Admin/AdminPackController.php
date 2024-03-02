<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use Illuminate\View\View;

class AdminPackController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}

	/**
	 * List products in pack
	 */
	public function index(string $id): View|RedirectResponse
	{
		$product = Product::findOrFail($id);

		if ($product->is_pack) {

			$items = $product->packProducts;

			$select_products = $items->count() ?
				Product::where('target', $product->target)->where('is_pack', 0)->whereNotIn('id', $items->modelKeys())->pluck('title_ca', 'id') :
				Product::where('target', $product->target)->where('is_pack', 0)->pluck('title_ca', 'id');

			return view('admin.product.pack', ['product' => $product, 'select_products' => $select_products]);
		} else {
			return redirect()->back()->with('message', 'Aquest producte no és un pack');
		}

	}


	/**
	 * Store products in pack
	 */
	public function store(string $product_id): RedirectResponse
	{
		$product = Product::findOrFail($product_id);
		$product->productesDelPack()->attach(request()->input('product_id'));
		return redirect()->back();
	}


	/**
	 * Remove product inside pack
	 */
	public function destroy(string $pack_id, string $product_id): RedirectResponse
	{
		$product = Product::findOrFail($pack_id);
		$product->productesDelPack()->detach($product_id);
		return redirect()->route('admin.pack.index', $pack_id)
			->with('message', 'Producte eliminat del pack.');

	}

}