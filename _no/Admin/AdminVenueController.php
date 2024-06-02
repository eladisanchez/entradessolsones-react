<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Venue;
use File;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminVenueController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}

	/**
	 * List of all venues
	 */
	public function index(): View
	{
		$venues = Venue::with('products')->get();
		return view('admin.venue.index', ['venues' => $venues]);
	}


	/**
	 * New venue form
	 */
	public function create(): View
	{
		return view('admin.venue.create');
	}


	/**
	 * Store new venue
	 */
	public function store(): RedirectResponse
	{
		$venue = Venue::create(request()->input('parells'));
		return redirect()->route('admin.venue.index')
			->with('message', 'Espai <strong>' . $venue->name . '</strong> creat correctament.');
	}


	/**
	 * Edit venue form
	 */
	public function edit(string $id): View
	{
		$venue = Venue::findOrFail($id);
		return view('admin.venue.edit', ['venue' => $venue]);
	}


	/** 
	 * Image upload
	 */
	public function image(string $id): RedirectResponse
	{
		$venue = Venue::findOrFail($id);

		if (request()->hasFile('image')) {

			$imatgeURL = request()->file('image')->getClientOriginalName();

			request()->file('image')->move('venues', $imatgeURL);

			$venue->pdf = $imatgeURL;
			$venue->save();

			return redirect()->route('admin.venue.edit', $id)
				->with('message', 'Arxiu pujat correctament');

		}

		return redirect()->route('admin.venue.edit', $id)
			->with('message', "No s'ha pujat cap arxiu");
	}


	/**
	 * 
	 */
	public function update($id): RedirectResponse
	{
		$venue = Venue::findOrFail($id);

		if (!$venue->update(request()->all())) {
			return redirect()->back()
				->with('message', 'Hi ha hagut un error.')
				->withInput();
		}

		return redirect()->route('admin.venue.index')
			->with('message', 'Espai editat correctament');
	}


	/**
	 * Delete venue
	 */
	public function destroy($id): RedirectResponse
	{
		$venue = Venue::findOrFail($id);
		File::delete(public_path() . 'venues/' . $venue->pdf);
		$venue->delete();

		return redirect()->route('admin.venue.index')->with('message', 'Espai eliminat');
	}

}
