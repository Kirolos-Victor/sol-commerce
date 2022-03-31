<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Location;
use App\Models\Category;

class LocationsController extends Controller
{
    public function index(Request $request)
    {
		// validate
		$request->validate([
			'postcode' => ['required'],
		]);

		$data = $request->all();

		$location = Location::whereRaw('FIND_IN_SET('.$data['postcode'].', postcodes)')->first();

		if (!$location) {
			$location = Location::find(1);
			$location->non_perishable = true;
		} else {
			$location->non_perishable = false;
		}

		return response()->json([
			'location' => $location
		]);

		// return \Inertia\Inertia::render('', [
		// 	'location' => $location,
		// ]);
    }
}
