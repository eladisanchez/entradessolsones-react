<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Refund;
use App\Mail\RefundMail;
use Illuminate\Http\Request;
use Mail;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class AdminTicketController extends Controller
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}

	/**
	 * List of all available tickets
	 */
	public function index(string $id): View
	{
		$product = Product::find($id);
		$tickets = request()->query('anteriors') ? 
			Ticket::where('product_id', $id)->where('day', '=', request()->query('anteriors'))->order('day', 'DESC')->paginate(100) :
			Ticket::where('product_id', $id)->where('day', '>=', Carbon::now()->toDateString())->paginate(100);
		$days = array();

		foreach ($tickets as $ticket) {
			$diastring = $ticket->day->toDateString();
			$days[$diastring]['day'] = $ticket->day->format('D d/m/Y');
			$days[$diastring]['hours'][] = [
				'hour' => $ticket->hour->format('H:i'),
				'tickets' => $ticket->tickets,
				'available' => $ticket->available,
				'language' => $ticket->language,
				'cancelled' => $ticket->cancelled
			];
			if (!isset($days[$diastring]['disponiblesDia'])) {
				$days[$diastring]['disponiblesDia'] = 0;
				$days[$diastring]['entradesDia'] = 0;
			}
			$days[$diastring]['disponiblesDia'] += $ticket->disponibles;
			$days[$diastring]['entradesDia'] += $ticket->entrades;
		}

		return view('admin.ticket.index', [
			'product' => $product,
			'tickets' => $tickets,
			'days' => $days
		]);
	}


	/**
	 * Report for organizers
	 */
	public function indexReport($id): View
	{
		$product = Product::find($id);
		$entrades = Ticket::where('product_id', $id);
		$now = Carbon::now()->toDateString();

		$tickets = request()->input('ant') ?
			Ticket::where('Product_id', $id)->where('day', '<', $now)->orderBy('day', 'DESC')->paginate(60) : Ticket::where('Product_id', $id)->where('day', '>=', $now)->paginate(60);

		$days = array();
		foreach ($tickets as $ticket) {
			$diastring = $ticket->day->toDateString();
			$days[$diastring]['day'] = $ticket->day->format('Y-m-d');
			$days[$diastring]['hores'][] = array(
				'hour' => $ticket->hour->format('H:i'),
				'horaok' => $ticket->hour->toTimeString(),
				'entrades' => $ticket->entrades,
				'disponibles' => $ticket->disponibles
			);
			if (!isset($days[$diastring]['disponiblesDia'])) {
				$days[$diastring]['disponiblesDia'] = 0;
				$days[$diastring]['entradesDia'] = 0;
			}
			$days[$diastring]['disponiblesDia'] += $ticket->disponibles;
			$days[$diastring]['entradesDia'] += $ticket->entrades;
		}

		return view(
			'report.producte',
			[
				'product' => $product,
				'days' => $days,
				'tickets' => $tickets
			]
		);
	}


	/**
	 * Store a newly created ticket in storage.
	 */
	public function store(Request $request): RedirectResponse
	{

		// Data i hora obligatòries
		$validator = $request->validate([
			'data-inici' => 'required',
			'hour' => 'required',
		]);

		$product_id = request()->input('Product_id');
		$product = Product::find($product_id);

		// Dies de la setmana. X generar calendaris ràpidament
		$w = [0, 1, 2, 3, 4, 5, 6];
		if (request()->has('w'))
			$w = request()->input('w');

		// Eliminar les existents si es marca l'opció
		if (request()->input('neteja') == 1) {
			Ticket::where('Product_id', $product_id)->delete();
		}

		// Periode
		$begin = new DateTime(request()->input('data-inici'));
		$end = request()->has('data-fi') ?
			new DateTime(request()->input('data-fi')) :
			new DateTime(request()->input('data-inici'));

		$end->modify('+1 day'); // Include last day

		$interval = new DateInterval('P1D');
		$period = new DatePeriod($begin, $interval, $end);

		$entradesCreades = 0;

		// With hour
		if (request()->input('hour')):

			$hour = Carbon::createFromFormat('H:i', request()->input('hour'))->toTimeString();

			foreach ($period as $dt) {

				$dia = $dt->format("Y-m-d");
				$diaw = $dt->format('w');

				$ticket = Ticket::where("Product_id", $product_id)
					->where("dia", $dia)->where("hora", request()->input('hour'))->first();

				if ($product->venue || in_array($diaw, $w)) {

					if ($ticket === null) {

						$ticket = new Ticket;
						$ticket->product_id = $product_id;
						$ticket->day = $dia;
						$ticket->hour = $hour;

						if (!$product->venue) {

							$ticket->language = request()->input('idioma');
							$ticket->tickets = request()->input('entrades');

						} else {

							$localitats = $product->espai->seats;
							$ticket->seats = $localitats;
							$ticket->entrades = count(json_decode($product->espai->seats));

						}

						$ticket->save();
						$entradesCreades++;

					}

				}

			}

		endif;

		return redirect()->route('admin.ticket.index', $product_id)
			->with('message', $entradesCreades . ' entrades creades del ' . $begin->format('d/m/Y') . ' al ' . $end->format('d/m/Y'));

	}


	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id, string $dia, string $hour): View
	{

		$ticket = Ticket::where("product_id", $id)->where("dia", $dia)->where("hora", $hour)->first();
		$product = $ticket->product;

		if ($product->venue) {
			return view('admin.ticket.edit-seats', ['ticket' => $ticket, 'product' => $product]);
		} else {
			return view('admin.ticket.edit', ['ticket' => $ticket, 'product' => $product]);
		}

	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(): RedirectResponse
	{

		if (request()->input('localitats')) {
			$entrades = count(json_decode(request()->input('localitats')));
		} else {
			$entrades = request()->input('entrades');
		}

		$ticket = Ticket::where("Product_id", request()->input('Product_id'))
			->where("dia", request()->input('day'))->where("hora", request()->input('hour'))
			->update(
				array(
					'entrades' => $entrades,
					'localitats' => request()->input('localitats'),
					'idioma' => request()->input('idioma')
				)
			);

		if (!$ticket) {
			return redirect()->back()
				->with('message', 'Error a l\'editar les disponibilitats')
				->withInput();
		}

		return redirect()->route('admin.ticket.index', request()->input('product_id'))
			->with('message', 'Ticket editada correctament');

	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id, string $day, string $hour): RedirectResponse
	{

		$ticket = Ticket::where("product_id", $id)
			->where("day", $day)
			->where("hour", $hour)
			->firstOrFail();

		if ($ticket->cancelled) {
			return request()->route('admin.ticket.index', $id)
				->with('message', 'Aquesta sessió ja està cancel·lada');
		}

		// Create new tickets if is a day change
		$day_change = false;
		if (request()->input('new-day') && request()->input('new-hour')) {
			$day_change = true;
			Ticket::create([
				'product_id' => $id,
				'day' => request()->input('new-day'),
				'hour' => request()->input('new-hour'),
				'language' => $ticket->language,
				'seats' => $ticket->seats,
				'tickets' => $ticket->tickets
			]);
		}

		// Bookings for the cancelled session ordered by date
		$bookings = Booking::where("product_id", $id)->where("day", $day)->where("hour", $hour)->get();
		$collection = $bookings->groupBy('order_id');
		$collection->all();
		if (count($bookings)) {

			// Set bookings as refundable
			foreach ($collection as $order_id => $bookings) {

				$total = 0;

				foreach ($bookings as $booking) {

					$booking->refund = 1;
					if ($day_change) {
						$booking->day = request()->input('new-day');
						$booking->hour = request()->input('new-hour') . ':00';
					}
					$booking->save();
					$total += $booking->price * $booking->tickets;

				}

				// Create refund for only paid orders
				$order = Order::find($order_id);
				if ($order && $order->paid && $order->payment == 'card') {

					$refund = new Refund([
						'order_id' => $order_id,
						'total' => $total,
						'hash' => sha1(time() . '_' . $order_id)
					]);
					$refund->product_id = $id;
					$refund->new_day = $day_change ?
						request()->input('new-day') . ' ' . request()->input('new-hour') . ':00' :
						null;
					$refund->day_cancelled = $day . ' ' . $hour;
					$refund->save();

					Mail::to($order->email)->send(new RefundMail($refund));

				}
			}

			// Mark tickets cancelled
			Ticket::where("product_id", $id)
				->where("day", $day)
				->where("hora", $hour)
				->update(['cancelled' => 1]);

			return redirect()->route('admin.ticket.index', $id)
				->with('message', 'Sessió cancel·lada per al ' . $day . ' a les ' . $hour);

		}

		// No hi ha reserves fetes, ens carreguem directament les entrades creades.
		$ticket = Ticket::where("product_id", $id)->where("day", $day)->where("hour", $hour)->delete();
		return redirect()->route('admin.ticket.index', $id)
			->with('message', 'Entrades eliminades per al ' . $day . ' a les ' . $hour);

	}


	/**
	 * Remove product tickets for an entire day
	 */
	public function destroyDay(string $id, string $day): RedirectResponse
	{
		Ticket::where("product_id", $id)->where('day', $day)->delete();
		return redirect()->route('admin.ticket.index', $id)
			->with('message', 'Disponibilitats pel dia ' . $day . ' eliminades.');
	}


	/**
	 * Show seat map for a session
	 */
	public function map(string $id, string $day, string $hour): View
	{
		$product = Product::find($id);
		$tickets = $product->ticketsDay($day, $hour);
		return view('admin.ticket.map', ['entrades' => $tickets]);
	}

}