<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Venue;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class VenueMapController extends BaseController
{
    public function edit(string $id): InertiaResponse
	{
		$venue = Venue::findOrFail($id);
		return Inertia::render('Admin/VenueMap', [
			'venue' => $venue,
		]);
	}

    public function update($id)
	{
		$venue = Venue::findOrFail($id);
		$venue->seats = request()->input('seats');
		$venue->save();
		if (!$venue->save()) {
			return ['error'];
		}
		return ['ok'];
	}
}
