<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCategoryController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'products'
		]);
	}

	/**
	 * Show all categories
	 */
	public function index(): View
	{
		$categories = Category::with('products')->orderBy('order')->get();
		return view('admin.category.index', array('categories' => $categories));
	}


	/**
	 * New category form
	 */
	public function create(): View
	{
		return view('admin.category.create');
	}

	/**
	 * Store category
	 */
	public function store(): RedirectResponse
	{
		$category = Category::create(request()->all());
		return redirect()->route('admin.category.index')
			->with('message', 'Categoria <strong>' . $category->title . '</strong> creada correctament.');
	}

	/**
	 * Category edit form
	 */
	public function edit(string $id): View
	{
		$category = Category::find($id);
		return view('admin.category.index', ['categoria' => $category]);
	}

	/**
	 * Update existing category
	 */
	public function update(string $id): RedirectResponse
	{
		$cat = Category::find($id);

		if (!$cat->update(request()->all())) {
			return redirect()->back()
				->with('message', 'Something wrong happened while saving your model')
				->withInput();
		}

		return redirect()->route('admin.category.index')
			->with('message', 'Categoria editada correctament');

	}

	// TODO: Destroy category
	/**
	 * Delete category
	 */
	public function destroy(string $id)
	{
		//
	}



}