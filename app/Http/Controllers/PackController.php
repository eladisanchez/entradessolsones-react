<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use Session;

class PackController extends BaseController
{


	/**
	 * Start a new pack in user session
	 */
	public function registerPack($id): RedirectResponse
	{

		$pack = Product::with('rates')->findOrFail($id);

		$rates = request()->input('rate');
		$qtys = request()->input('qty');

		$totalqty = array_sum($qtys);

		Session::forget('pack' . $pack->id);

		Session::put('pack' . $pack->id . '.rates', $rates);
		Session::put('pack' . $pack->id . '.qtys', $qtys);
		Session::put('pack' . $pack->id . '.total', $totalqty);

		return redirect()->back();

	}


	/**
	 * Delete a product from pack
	 */
	public function removeProduct(string $pack_id, string $product_id): RedirectResponse
	{
		Session::forget('pack' . $pack_id . '.bookings.' . $product_id);
		return redirect()->back();
	}


	/**
	 * Delete full pack from session
	 */
	public function deletePack($id)
	{
		Session::forget('pack' . $id);
		return redirect()->back();
	}


	/**
	 * Add new product to pack session
	 */
	public function addProduct()
	{

		$pack_id = request()->input('pack_id');
		$product_id = request()->input('product_id');
		$day = request()->input('day');
		$hour = request()->input('hour');
		$seat = request()->input('seat') ? json_decode(request()->input('seat'))[0] : '';
		$pack = Product::findOrfail($pack_id);

		if ($pack) {
			Session::put('pack' . $pack_id . '.bookings.' . $product_id . '.day', $day);
			Session::put('pack' . $pack_id . '.bookings.' . $product_id . '.hour', $hour);
			Session::put('pack' . $pack_id . '.bookings.' . $product_id . '.seat', $seat);
			Session::put('pack' . $pack_id . '.bookings.' . $product_id . '.product', $product_id);
			return redirect()->route('product.show', $pack->name);
		} else {
			return redirect()->back()->with(['message' => "No s'han pogut afegir les entrades al pack."]);
		}

	}

}