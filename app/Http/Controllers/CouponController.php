<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Session;
use Illuminate\Http\RedirectResponse;

class CouponController extends BaseController
{


	public function apply(): RedirectResponse
	{

		$coupons = Coupon::where('coupon', request()->input('coupon'))
			->where('validity', '>', date('Y-m-d'))
			->get();

		if (count($coupons) > 0) {

			if (!Session::has('codi')) {

				foreach ($coupons as $codi) {

					if (!Session::has('codi.p' . $codi->producte_id . '_t' . $codi->tarifa_id)) {

						Session::put('codi.p' . $codi->producte_id . '_t' . $codi->tarifa_id, true);

						$productsCistell = Cart::search(function ($q, $v) use ($codi) {
							return $q->options->id_producte == $codi->producte_id && $q->options->id_tarifa == $codi->tarifa_id;
						});

						if ($productsCistell) {
							foreach ($productsCistell as $prod) {
								$noupreu = $prod->price * (1 - $codi->descompte / 100);
								Cart::update($prod->rowId, array('price' => $noupreu));
							}
						}

					}

				}

				Session::put('codi.nom', request()->input('codi'));
				Session::put('codi.descompte', $codi->descompte);
				return redirect()->route('cart')->with('message', 'Codi promocional correcte!');

			}

			return redirect()->route('cart')->with('message', 'Ja has aplicat un descompte');

		}

		// Codi incorrecte
		else {
			return redirect()->route('cart')->with('message', 'El codi promocional no és vàlid.');
		}

	}

}