<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Product;
use Illuminate\View\View;

class AdminUserController extends BaseController
{

	public function __construct()
	{
		view()->share([
			'menu' => 'users'
		]);
	}

	/**
	 * List all users by type
	 */
	public function index(): View
	{
		$admins = User::withRole('admin')->where('id', '<>', 1)->get();
		$entities = User::withRole('organizer')->get();
		$validators = User::withRole('validator')->get();
		$users = $admins->merge($entities)->merge($validators);
		return view('admin.user.index', ['users' => $users]);
	}


	// TODO: Check view and menu
	/**
	 * List only clients
	 */
	public function indexClients(): View
	{
		$users = User::whereDoesntHave('roles', function ($query) {
			$query->where('name', 'admin')->orWhere('name', 'organizer');
		})->paginate(30);
		return view('admin.user.index', ['usuaris' => $users]);
	}


	/**
	 * Create new user
	 */
	public function store()
	{
		$user = new User;
		$user->username = request()->input('username');
		$user->password = request()->input('password');
		$user->email = request()->input('email');
		$user->save();
		if (request()->input('role') == 'admin') {
			$user->attachRole(1);
		}
		if (request()->input('role') == 'organizer') {
			$user->attachRole(3);
		}
		if (request()->input('role') == 'validator') {
			$user->attachRole(6);
		}
		return redirect()->route('admin.user.index')->with('message', 'Usuari creat correctament');
	}


	/**
	 * Edit user
	 */
	public function edit(string $id): View
	{

		$user = User::findOrFail($id);

		$products = $user->productes;

		$select_products = $products->count() ?
			Product::whereNotIn('id', $products->modelKeys())->pluck('title', 'id') :
			Product::pluck('title', 'id');

		return view('admin.user.edit', ['user' => $user, 'products' => $select_products]);
	}


	/** 
	 * Save user products
	 */
	public function update(string $id)
	{

		$validator = validator(request()->all(), [
			'password' => 'confirmed',
		]);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$user = User::findOrFail($id);

		$user->email = request()->input('email');
		$user->conditions = request()->input('conditions');
		if (request()->input('password')) {
			$user->password = request()->input('password');
		}
		$user->save();

		if (request()->input('role') == 'admin' && !$user->hasRole('admin')) {
			$user->detachRole(3);
			$user->detachRole(6);
			$user->attachRole(1);
		}
		if (request()->input('role') == 'organizer' && !$user->hasRole('organizer')) {
			$user->detachRole(1);
			$user->detachRole(6);
			$user->attachRole(3);
		}
		if (request()->input('role') == 'validator' && !$user->hasRole('validator')) {
			$user->detachRole(1);
			$user->detachRole(3);
			$user->attachRole(6);
		}

		return redirect()->route('admin.user.index')->with('message', 'Usuari editat correctament.');

	}


	/**
	 * Destroy user
	 */
	public function destroy(string $id): RedirectResponse
	{

		$user = User::findOrFail($id);
		$user->delete();
		return redirect()->back()->with('message', 'Usuari eliminat.');

	}


	/**
	 * Attach product to user
	 */
	public function addProduct($id)
	{

		$user = User::findOrFail($id);
		$user->products()->attach(request()->input('product_id'));
		return redirect()->back();
	}

	/**
	 * Detach product from user
	 */
	public function detachProduct(string $user_id, string $product_id)
	{

		$user = User::findOrFail($user_id);
		$user->productes()->detach($product_id);
		return redirect()->back()->with('message', 'Producte desvinculat de l\'usuari.');

	}


	/**
	 * Front login for clients
	 */
	public function clientLogin(): RedirectResponse
	{
		$user = [
			'email' => request()->input('email'),
			'password' => request()->input('password')
		];
		if (auth()->attempt($user)) {
			return redirect()->back();
		}
		return redirect()->back()
			->with('flash_error', 'La combinació usuari/contrasenya és incorrecta.')
			->withInput();
	}

	/**
	 * Login as client
	 */
	public function loginAs($id): RedirectResponse
	{
		$user = User::findOrFail($id);
		auth()->login($user);
		return redirect()->route('report.index');
	}

	/**
	 * Logout
	 */
	public function logout()
	{
		auth()->logout();
		return redirect()->to('ca');
	}

}
