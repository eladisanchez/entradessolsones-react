<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use App\Models\Product;
use Redirect;
use Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCouponController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'coupons'
		]);
	}

	/**
	 * List all coupon codes
	 */
	public function index(): View
	{

		$codis = Coupon::with(array('product', 'rate'))->orderby('code')->get();
		$codisArray = $codis->groupBy('code');

		$products = Product::with([
			'rates' => function ($query) {
				$query->select('rate_id', 'title_ca');
			}]
		)->where('active', 1)->get(array('id', 'title_ca'));

		$productarifes = array();
		foreach ($products as $product) {
			foreach ($product->rates as $rate) {
				$productarifes[$product->id . ':' . $rate->rate_id] = $product->title_ca . ' - ' . $rate->title;
			}
		}

		return view('admin.coupon.index', [
			'coupons' => $codisArray,
			'products' => $productarifes
		]);

	}

	public function store(): RedirectResponse
	{

		$rates = request()->input('rates');
		$input = request()->all();
		unset($input["rates"]);

		foreach ($rates as $rate) {
			$t = explode(':', $rate);
			$values = array_merge($input, ['product_id' => $t[0], 'rate_id' => $t[1]]);
			Coupon::create($values);
		}

		return redirect()->back()->with('message', 'Codi <strong>' . $input["codi"] . '</strong> creat');

	}


	/**
	 * Delete single coupon
	 */
	public function destroy(): RedirectResponse
	{

		$coupon = Coupon::with(['product', 'rate'])->findOrFail(request()->input('coupon_id'));
		if (!$coupon) {
			return redirect()->back();
		}
		$codinom = $coupon->codi;
		$codiprod = $coupon->product ? $coupon->product->title_ca . ' - ' . $coupon->rate->title_ca : null;
		$coupon->delete();
		return Redirect::back()->with('message', 'Codi <strong>"' . $codinom . '"</strong> eliminat per a <strong>"' . $codiprod . '"</strong>');

	}


	/**
	 * Delete all coupons by code
	 */
	public function destroyAll(): RedirectResponse
	{

		$codinom = request()->input('coupon_id');
		$coupons = Coupon::where('code', $codinom)->get(['id']);
		Coupon::destroy($coupons->toArray());
		return redirect()->back()->with('message', 'S\'han eliminat tots els descomptes amb el codi <strong>"' . $codinom . '"</strong>');

	}


}