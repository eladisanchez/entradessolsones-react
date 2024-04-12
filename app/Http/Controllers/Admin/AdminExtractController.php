<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Extract;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Venue;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExtractExport;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminExtractController extends BaseController
{

	/**
	 * Extract dashboard
	 */
	public function index(): View
	{

		$users = User::withRole('organizer')->pluck('username', 'id')->toArray();
		$users = [null => '-'] + $users;
		$products = Product::get()->pluck('title_ca', 'id')->toArray();
		$products = [null => '-'] + $products;
		$extracts = Extract::with(['product', 'user'])->orderBy('date_end', 'desc')->paginate(20);
		return view(
			'admin.extract.index',
			[
				'extracts' => $extracts,
				'users' => $users,
				'products' => $products
			]
		);

	}


	/**
	 * Export to excel
	 */
	public function excel(string $id): BinaryFileResponse
	{
		$extract = Extract::findOrFail($id);
		$nom_excel = $extract->user ? $extract->user->username : $extract->product->name ?? 'Tots els productes';
		return Excel::download(new ExtractExport($extract), 'Extracte ' . $extract->date_start->format('Y-m-d') . ' ' . $nom_excel . '.xlsx');
	}


	/**
	 * Save new extract
	 */
	public function store(): RedirectResponse
	{
		$inputs = request()->all();
		if ($inputs["product_id"]) {
			$inputs["user_id"] = null;
		}
		Extract::create($inputs);
		return redirect()->route('admin.extract.index')
			->with('message', 'Extracte creat correctament.');
	}


	/**
	 * Edit existing extract
	 */
	// TODO: It's not finished.
	public function edit(string $id): View
	{
		$product = Product::findOrFail($id);
		$cats = Category::pluck('title_ca', 'id');
		$venues = [null => '-'] + Venue::pluck('name', 'id')->toArray();
		$products = Product::where('id', '<>', $product->id)->pluck('title_ca', 'id')->toArray();
		return view('admin.productes.producte', ['product' => $product, 'categories' => $cats, 'products' => $products, 'espais' => $venues]);
	}


	/**
	 * Toggle extract payment status
	 */
	public function togglePaid($id)
	{
		$extract = Extract::findOrFail($id);
		$extract->paid = $extract->paid == 1 ? 0 : 1;
		$extract->save();
		return redirect()->route('admin.extract.index')
			->with('message', 'Estat de l\'extracte actualitzat');
	}


	/**
	 * Update extract
	 */
	public function update(string $id): RedirectResponse
	{
		$extract = Extract::findOrFail($id);
		$values = request()->all();

		if (!$extract->update($values)) {
			return redirect()->back()
				->with('message', 'Error.')
				->withInput();
		}

		return redirect()->route('admin.extract.index')
			->with('message', 'Extracte editat correctament');
	}


	/**
	 * Delete extract
	 */
	public function delete(string $id): RedirectResponse
	{
		$extract = Extract::findOrFail($id);
		$extract->delete();
		return redirect()->route('admin.extract.index')->with('message', 'Extracte eliminat');
	}

}
