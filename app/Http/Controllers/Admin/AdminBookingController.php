<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use App\Models\Booking;
use Illuminate\View\View;

class AdminBookingController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'bookings'
		]);
	}

	/**
	 * List of all bookings filtered by product
	 */
	public function index()
	{

		$products = array('' => '') + Product::pluck('title', 'id')->toArray();

		$bookings = Booking::with('product', 'order', 'rate', 'scans')->orderBy('hour');

		if (request()->input('day')) {
			$bookings->where('day', request()->input('day'));
		} else {
			$bookings->orderBy('day');
		}

		if (request()->has('id')) {
			$bookings->where('product_id', request()->input('id'));
		}

		$bookings = $bookings->paginate(30);

		return view('admin.booking.index', [
			'bookings' => $bookings, 
			'products' => $products
		]);

	}


	/**
	 * Edit booking form
	 */
	public function edit(string $id): View
	{
		$booking = Booking::findOrFail($id);
		return view('admin.booking.edit', ['booking' => $booking]);
	}


	/**
	 * Update booking
	 */
	public function update(string $id): RedirectResponse
	{

		$booking = Booking::findOrFail($id);

		if (!$booking->update(request()->all())) {
			return redirect()->back()
				->with('message', 'Error.')
				->withInput();
		}

		return redirect()->back()->with('message', 'Reserva editada correctament.');

	}


	/**
	 * Delete booking
	 */
	public function destroy(string $id)
	{
		$booking = Booking::findOrfail($id);
		$booking->delete();
		return redirect()->back()->with('message', 'Reserva eliminada.');

	}

}
