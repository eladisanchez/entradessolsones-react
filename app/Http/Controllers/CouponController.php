<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Session;
use Illuminate\Http\JsonResponse;

class CouponController extends BaseController
{


	public function apply(): JsonResponse
	{

		if (Session::has('coupon')) {
			return response()->json([
				'items' => Cart::instance('shopping')->content(),
				'total' => Cart::instance('shopping')->total(),
				'status' => 'error',
				'message' => 'Ja has aplicat un descompte'
			]);
		}

		$coupons = Coupon::where('code', request()->input('code'))
			->where('validity', '>', date('Y-m-d'))
			->get();

		if (!count($coupons)) {
			return response()->json([
				'items' => Cart::instance('shopping')->content(),
				'total' => Cart::instance('shopping')->total(),
				'status' => 'error',
				'message' => 'El codi promocional no és vàlid.'
			]);
		}

		foreach ($coupons as $coupon) {
			if (!Session::has('coupon.p' . $coupon->product_id . '_t' . $coupon->rate_id)) {
				Session::put('coupon.p' . $coupon->product_id . '_t' . $coupon->rate_id, true);
				$cartProducts = Cart::instance('shopping')->search(function ($q, $v) use ($coupon) {
					return $q->options->product_id == $coupon->product_id && $q->options->rate_id == $coupon->rate_id;
				});
				if ($cartProducts) {
					foreach ($cartProducts as $product) {
						$newPrice = $product->price * (1 - $coupon->discount / 100);
						Cart::update($product->rowId, array('price' => $newPrice));
					}
				}
			}
		}
		Session::put('coupon.name', request()->input('code'));
		Session::put('coupon.discount', $coupon->discount);

		return response()->json([
			'items' => Cart::instance('shopping')->content(),
			'total' => Cart::instance('shopping')->total(),
			'status' => 'success',
			'message' => 'Codi aplicat correctament'
		]);

	}

}