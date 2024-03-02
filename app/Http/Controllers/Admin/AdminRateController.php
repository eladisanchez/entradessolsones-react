<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\Rate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminRateController extends Controller
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}


	/**
	 * All rates
	 */
	public function index(): View
	{
		$rates = Rate::with('product')->orderBy('order')->get();
		return view('admin.rate.index', ['rates' => $rates]);
	}


	/**
	 * New rate form
	 */
	public function create(): View
	{
		return view('admin.rate.create');
	}


	/**
	 * Save new rate
	 */
	public function store(): RedirectResponse
	{
		$rate = Rate::create(request()->all());
		return redirect()->route('admin.rate.index')
			->with('message', 'Tarifa <strong>' . $rate->title . '</strong> creada correctament.');
	}


	/**
	 * Edit rate form
	 */
	public function edit(string $id): View
	{
		$rate = Rate::findOrFail($id);
		return view('admin.rate.edit',['rate' => $rate]);
	}


	/**
	 * Save existing rate
	 */
	public function update(string $id): RedirectResponse
	{
		$rate = Rate::findOrFail($id);

		if (!$rate->update(request()->all())) {
			return redirect()->back()
				->with('message', 'Something wrong happened while saving your model')
				->withInput();
		}

		return redirect()->route('admin.rate.index')
			->with('message', 'Tarifa editada correctament');
	}


	/**
	 * Delete rate
	 */
	public function destroy(string $id): RedirectResponse
	{
		$tarifa = Rate::findOrFail($id);
		$tarifa->delete();
		return redirect()->route('admin.rate.index')
			->with('message', 'Tarifa eliminada');
	}

}
