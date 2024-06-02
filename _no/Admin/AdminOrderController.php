<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use App\Models\Order;
use App\Mail\NewOrder;
use Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Monarobase\CountryList\CountryListFacade as Countries;

class AdminOrderController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'orders'
		]);
	}

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
	 * List all orders
	 */
	public function index(): View
	{

		/**
		 * Clean non-processed orders
		 */
		$this->cleanNonProcessed();

		if (request()->has('id')) {
			$orders = Order::with('bookings.product')->where('id', 'LIKE', '%' . request()->input('id') . '%')->orderBy('created_at', 'DESC')->paginate(30);
		} elseif (request()->has('day') && !empty(request()->input('day'))) {
			$day_start = Carbon::createFromFormat('d-m-Y', request()->input('dia'));
			$day_end = Carbon::createFromFormat('d-m-Y', request()->input('dia'));
			$start = $day_start->hour(0)->minute(0)->second(0);
			$end = $day_end->hour(23)->minute(59)->second(59);
			$orders = Order::with('bookings.product')->whereBetween('created_at', array($start, $end))->orderBy('created_at', 'DESC')->paginate(30);
		} elseif (request()->input('trashed')) {
			$orders = Order::onlyTrashed()->with('bookings.product')->orderBy('created_at', 'DESC')->paginate(30);
		} else {
			$orders = Order::with('bookings.product')->orderBy('created_at', 'DESC')->paginate(30);
		}

		return view('admin.order.index', ['orders' => $orders]);
	}


	/**
	 * Order edit form
	 */
	public function edit(string $id): View
	{

		$order = Order::withTrashed()->with('bookings.scans')->findOrFail($id);
		$countries = Countries::getList('ca', 'php');
		return view('admin.order.edit', [
			'order' => $order,
			'countries' => $countries
		]);

	}


	/**
	 * Update order
	 */
	public function update($id)
	{

		$cat = Order::withTrashed()->findOrFail($id);

		// Restore order from trash if payment is set to OK
		if ($cat->trashed() && request()->input('paid') == 1) {
			$cat->restore();
		}

		if (!$cat->update(request()->all())) {
			return redirect()->back()
				->with('message', 'Error.')
				->withInput();
		}

		return redirect()->route('admin.order.index')->with('message', 'Comanda editada correctament');

	}


	/**
	 * Destroy order
	 */
	public function destroy($id): RedirectResponse
	{
		$order = Order::findOrFail($id);
		$order->delete();
		return redirect()->route('admin.order.index');
	}


	/** 
	 * Resent order mail to client
	 */
	public function resendMail($id)
	{
		$order = Order::findOrFail($id);
		Mail::to($order->email)->send(new NewOrder($order));
		return redirect()->route('admin.order.index')->with('message', 'Email de comanda enviat a l\'usuari ' . $order->email);
	}

}