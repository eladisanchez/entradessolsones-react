<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Booking;
use App\Models\User;
use App\Mail\NewOrder;
use Mail;
use Session;
use LaravelLocalization;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Shanmuga\LaravelEntrust\Facades\LaravelEntrustFacade as Entrust;

class OrderController extends BaseController
{


	/**
	 * Clean non-processed orders
	 */
	public static function cleanNonProcessed(): void
	{
		$date = new \DateTime;
		$date->modify('-60 minutes');
		$formatted = $date->format('Y-m-d H:i:s');
		Order::where('paid', '!=', 1)
			->where('payment', 'card')
			->where('created_at', '<=', $formatted)
			->delete();
		return;
	}


	/**
	 * Cart confirmation, store order
	 */
	public function store(): RedirectResponse
	{

		$failedOrder = Order::where('session', Session::getId())->where('paid', 0)->where('payment', 'card')->orderBy('created_at', 'desc')->first();
		if ($failedOrder) {
			return redirect()->route('checkout-tpv-ko', ['id' => $failedOrder->id]);
		}

		$this->cleanNonProcessed();

		if (!Cart::instance('shoipping')->count()) {
			return redirect()->route('home');
		}

		// Delete order with same session id when user goes back
		$orderError = Order::where('session', Session::getId())
			->where('paid', 0)->where('payment', 'card')
			->first();
		if ($orderError) {
			$orderError->delete();
		}

		$rules = !Entrust::hasRole(['admin', 'organizer']) ? [
			'condicions' => 'accepted',
			'name' => 'required',
			'telefon' => 'required',
			'email' => 'required|email',
			'cp' => 'required|size:5'
		] : [
			'condicions' => 'accepted',
			'name' => 'required',
			'email' => 'required|email',
		];

		$validator = validator(request()->all(), $rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		// Create new user if password is submitted
		if (request()->has('password') && !empty(request()->input('password'))) {
			$validatorU = validator(request()->all(), [
				'password' => 'confirmed|min:6',
				'email' => 'unique:users,email'
			]);
			if ($validatorU->fails()) {
				return redirect()->back()->withErrors($validatorU)->withInput();
			}
			$user = new User;
			$user->username = request()->input('name');
			$user->email = request()->input('email');
			$user->password = request()->input('password');
			$user->save();
		}


		// Check availabilities before checkout
		foreach (Cart::content() as $row) {

			// Venue events
			if ($row->options->seat) {
				$booked = Booking::where('product_id', $row->model->id)
					->where('day', $row->options->dia)
					->where('hour', $row->options->hora)
					->where('seat', json_encode($row->options->seat))
					->whereHas('order', function ($query) {
						$query->whereNull('deleted_at');
					})
					->first();
				if ($booked) {
					return response()->back()->withErrors('Ho sentim, la localitat <strong>' . \App\Helpers\Common::seat($row->options->seat) . '</strong> per a <strong>' . $row->model->title . '</strong> ja ha sigut adquirida per un altre usuari. Si us plau, esculli una altra localitat.')->withInput();
				}
			} else {
				if ($row->model->is_pack) {
					// TODO: Programar que per cada producte del pack comprovi si queden entrades disponibles.
				} else {
					$tickets_day = $row->model->ticketsDia($row->options->day, $row->options->hour);
					if ($tickets_day->available < 0) {
						return response()->back()->with('message', 'Ho sentim, ja no hi ha entrades disponibles per al producte ' . $row->model->title . '. Redueixi la quantitat d\'entrades o canvii l\'hora o el dia de la visita.')->withInput();
					}
				}
			}

		}

		request()->merge([
			'language' => LaravelLocalization::setLocale(),
			'session' => Session::getId(),
			'total' => Cart::total(),
			'coupon' => Session::get('coupon.name'),
			'user_id' => $user->id ?? null
		]);

		$values = request()->except(['conditions', 'password', 'password_confirmation']);
		$order = Order::create($values);

		foreach (Cart::content() as $row) {

			// Reserves dels packs
			if ($row->model->is_pack) {

				$booking = new Booking;
				$booking->tickets = $row->qty;
				$booking->price = $row->price;
				$booking->is_pack = 1;
				$booking->product()->associate($row->model);
				$booking->rate()->associate($row->options->rate);
				$booking->order()->associate($order);

				$isr = true;

				foreach ($row->options->bookings as $subreserva) {

					$subproducte = Product::find($subreserva["producte"]);

					if ($isr == true) {
						$booking->day = $subreserva["day"];
						$booking->hour = $subreserva["hour"];
						$booking->save();
						$isr = false;
					}

					$sreserva = new Booking;
					$sreserva->day = $subreserva["day"];
					$sreserva->hour = $subreserva["hour"];
					$sreserva->tickets = $row->qty;
					$sreserva->price = 0;
					$sreserva->uniqid = substr(bin2hex(random_bytes(20)), -5);
					$sreserva->product()->associate($subproducte);
					$sreserva->rate()->associate($row->options->rate);
					$sreserva->order()->associate($order);
					$sreserva->save();

				}

			} else {

				$booking = new Booking;
				$booking->day = $row->options->day;
				$booking->hour = $row->options->hour;
				$booking->tickets = $row->qty;
				$booking->price = $row->price;
				$booking->uniqid = substr(bin2hex(random_bytes(20)), -5);
				if ($row->options->seat) {
					$booking->seat = json_encode($row->options->seat);
				}
				$booking->product()->associate($row->model);
				$booking->rate()->associate($row->options->rate);
				$booking->order()->associate($order);
				$booking->save();

			}


		}

		if ($order) {

			$payment = request()->input('payment');

			if ($order->total == 0) {
				$order->update(array('paid' => 1));
				$payment = 'credit';
			}

			switch ($payment):

				case 'credit':

					try {
						Mail::to($order->email)->send(new NewOrder($order));
					} catch (\Exception $e) {
						Log::error($e->getMessage());
					}

					Cart::destroy();
					Session::forget('coupon');
					Session::forget('coupon_name');

					// Missatge OK
					return View::make('checkout.credit-ok')->with('order', $order);

					break;

				case 'card':

					return View::make('checkout.tpv', ['order' => $order, 'entorn' => 'ok']);

					break;

			endswitch;



		}

		return redirect()->back()->with(['message' => 'Error al generar la comanda. Si us plau, posi\'s en contacte amb ' . config('mail.from.name')]);

	}


	public function checkoutTPV($id): View
	{
		$order = Order::findOrFail($id);
		if ($order->paid != 1) {
			return view('checkout.tpv', ['comanda' => $order, 'entorn' => 'ok']);
		} else {
			abort(404);
		}
	}


	public function tpvResponse(): string
	{
		try {

			if (!$_POST) {
				$_POST = $_GET;
			}

			$redsys = new \Sermepa\Tpv\Tpv();

			$key = config('redsys.key');
			$parameters = $redsys->getMerchantParameters($_POST["Ds_MerchantParameters"]);
			$DsResponse = $parameters["Ds_Response"];
			$DsResponse += 0;

			if ($redsys->check($key, $_POST) && $DsResponse <= 99) {

				$idcomanda = substr($parameters["Ds_Order"], 0, -3);
				$order = Order::find($idcomanda);

				if ($order) {

					// Missatge OK
					$order->update(
						array(
							'tpv_id' => $parameters["Ds_Order"],
							'pagat' => 1
						)
					);


					try {

						Mail::to($order->email)->send(new NewOrder($order));
						Mail::send('emails.avis', array('order' => $order), function ($message) use ($order) {
							$message->from(config('mail.from.address'), config('mail.from.name'));
							$message->to(config('mail.from.address'));
							$message->subject('Nova comanda a '.config('app.name').' (' . $order->email . ')');
						});

					} catch (\Exception $e) {
						Log::error($e->getMessage());
					}

				}

				return 'OK';

			} else {


				$idcomanda = substr($parameters["Ds_Order"], 0, -3);
				$order = Order::find($idcomanda);
				if ($order && $order->paid != 1) {
					$order->paid = 2;
					$order->save();
				}

				return 'NO';

			}

		} catch (\Exception $e) {

			Log::error($e->getMessage());
			return 'Caught exception: ' . $e->getMessage();

		}

	}


	/** 
	 * Payment successful.
	 */
	public function tpvOK(string $session, string $id): RedirectResponse
	{

		if ($session == Session::getId()) {

			$order = Order::where('session', Session::getId())->where('id', $id)->isPaid()->orderBy('created_at', 'desc')->firstOrFail();
			Cart::destroy();
			Session::forget('coupon');
			Session::forget('coupon_name');
			return view('checkout.tpv-ok')->with('order', $order);

		} else {

			return redirect()->route('home');

		}

	}


	/**
	 * Payment failed
	 */
	public function tpvKO(): View
	{

		$order = Order::where('session', Session::getId())
			->orderBy('created_at', 'desc')->where('paid', '!=', 1)
			->firstOrFail();

		return view('checkout.tpv-ko')->with('comanda', $order);

	}


	/**
	 * Generate order PDF with tickets
	 */
	public function pdf(string $session, string $id)
	{
		$order = Order::findOrFail($id);
		$tot = DB::table('options')->get();
		$values = array();
		foreach ($tot as $i) {
			$values[$i->key] = $i->value;
		}
		if ($order->session == $session && ($order->paid == 1 || $order->payment == 'credit')) {

			LaravelLocalization::setLocale($order->language);

			$pdf = Pdf::setOptions(['isRemoteEnabled' => true])->loadView(
				'pdf.tickets',
				[
					'order' => $order,
					'text' => $values
				]
			);

			return $pdf->stream('entrades-' . $id . '.pdf');
			
		} else {
			return abort('404');
		}
	}

}