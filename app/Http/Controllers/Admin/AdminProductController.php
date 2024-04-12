<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Venue;
use App\Models\User;
use Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AdminProductController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}

	/** 
	 * Table for all existing products
	 */
	public function index(): View
	{
		$active = !request()->query('disabled') == 1;
		$products = Product::with('category', 'parent')
			->where('active', $active)
			->orderBy('order', 'ASC')
			->get();
		return view('admin.product.index', ['products' => $products]);
	}


	/**
	 * Admin home for organizers
	 */
	public function indexReport(): View
	{
		$products = Auth::user()->products->where('active', 1)->sortByDesc('created_at');
		return view('report.home', array('products' => $products));
	}


	/**
	 * New product form
	 */
	public function create(): View
	{
		$cats = Category::pluck('title_ca', 'id');
		$venues = [null => '-'] + Venue::pluck('name', 'id')->toArray();
		$entities = [null => '-'] + User::withRole('entities')->pluck('username', 'id')->toArray();
		return view(
			'admin.product.create',
			[
				'categories' => $cats,
				'venues' => [null => '-'] + $venues,
				'entities' => [null => '-'] + $entities
			]
		);
	}


	/**
	 * Save new product
	 */
	public function store(): RedirectResponse
	{
		$values = request()->all();
		$values['name'] = \App\Helpers\Common::slugify(redirect()->input('title_ca'));
		$product = Product::create($values);
		return redirect()->route('admin.product.index')
			->with('message', 'Producte <strong>' . $product->title . '</strong> creat correctament.');
	}


	/**
	 * Edit product form
	 */
	public function edit(string $id): View
	{
		$product = Product::findOrFail($id);
		$cats = Category::pluck('title_ca', 'id');
		$venues = [null => '-'] + Venue::pluck('name', 'id')->toArray();
		$entities = [null => '-'] + User::withRole('organizer')->pluck('username', 'id')->toArray();
		$products = Product::where('id', '<>', $product->id)->pluck('title_ca', 'id')->toArray();
		return view('admin.product.edit', [
			'product' => $product,
			'categories' => $cats,
			'products' => $products,
			'venues' => $venues,
			'entities' => $entities
		]);
	}

	/**
	 * Save existing product
	 */
	public function update(string $id): RedirectResponse
	{
		$product = Product::findOrFail($id);

		$values = request()->all();
		$values["parent_id"] = $values["parent_id"] ?? 0;
		$values["is_pack"] = $values["is_pack"] ?? 0;
		$values["qr"] = $values["qr"] ?? 0;
		$values["social_distance"] ?? 0;

		if (!$product->update($values)) {
			return redirect()->back()
				->with('message', 'Error.')
				->withInput();
		}

		return redirect()->route('admin.product.index')
			->with('message', 'Producte editat correctament');

	}


	/**
	 * Delete product
	 */
	public function destroy(string $id): RedirectResponse
	{
		$product = Product::findOrFail($id);
		if ($product) {
			$product->delete();
			return redirect()->action('admin.product.index')->with('message', 'Producte eliminat');
		} else {
			return redirect()->back();
		}

	}


	/**
	 * Toggle active product
	 */
	public function active(string $id): RedirectResponse
	{
		$product = Product::findOrFail($id);
		$product->actiu = $product->actiu ? 0 : 1;
		$product->save();
		return redirect()->back()->with('message', "S'ha canviat l'estat d'activació del producte <strong>" . $product->title . "</strong>");
	}


	/**
	 * Update product order
	 */
	public function updateOrder(): JsonResponse
	{
		$input = request()->all();
		if (isset($input["order"])) {
			$order = explode(",", $input["order"]);
			for ($i = 0; $i < count($order); $i++) {
				Product::where('id', $order[$i])->update(['order' => $i]);
			}
			return response()->json([
				"status" => true,
				"message" => "Ordre actualitzat"
			]);
		}
		return response()->json([
			"status" => false,
			"message" => "Error updating order"
		]);
	}

	/**
	 * Update product image
	 */
	public function uploadImage(string $id): RedirectResponse
	{

		$product = Product::findOrFail($id);
		if (request()->hasFile('image')) {
			$move = request()->file('image')->move('images', $product->name . '.jpg');
			if ($move) {
				return redirect()->route('admin.product.edit', $id)
					->with('message', 'Imatge pujada correctament');
			}
		}
		return redirect()->route('admin.product.edit', $id)
			->with('message', 'Error al pujar la imatge');

	}

	/**
	 * Update product header image
	 */
	public function uploadHeaderImage(string $id): RedirectResponse
	{

		$product = Product::find($id);
		if (request()->hasFile('image')) {
			$move = request()->file('image')->move('images/header', $product->name . '.jpg');
			if ($move) {
				return redirect()->route('admin.product.edit', $id)
					->with('message', 'Capçalera pujada correctament');
			}
		}
		return redirect()->route('admin.product.edit', $id)
			->with('message', 'Error al pujar la imatge');

	}

	/**
	 * New product request form for organizers
	 */
	public function request(): View
	{
		$cats = Category::pluck('title_ca', 'id');
		$venues = [null => '-'] + Venue::pluck('name', 'id')->toArray();
		return view('report.nouproducte', [
			'categories' => $cats,
			'espais' => $venues
		]);
	}


	/**
	 * Send organizer new product request
	 */
	public function storeRequest(): RedirectResponse
	{
		request()->validate([
			'title_ca' => 'required|max:255',
			'title_es' => 'max:255'
		]);
		$info = request()->input('image');
		$info['name'] = \App\Helpers\Common::slugify(request()->input('title_ca'));
		$info['active'] = 0;
		Product::create($info);
		return redirect()->route('ProductController@request')->with('message', 'S\'ha enviat la sol·licitud');
	}


}