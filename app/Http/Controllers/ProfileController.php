<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		return \Inertia\Inertia::render('Account/Profile', [
			'user' => $user
		]);
    }

	public function store(Request $request)
    {
		$data = $request->only([
			'email',
			'first_name',
			'last_name',
			'phone',

			'shipping_address',
			'shipping_apartment',
			'shipping_city',
			'shipping_state',
			'shipping_postcode',
			'shipping_country',

			'billing_address',
			'billing_apartment',
			'billing_city',
			'billing_state',
			'billing_postcode',
			'billing_country',
		]);

		$user = Auth::user();

		$request->validate([
			'email' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'phone' => 'required',
			
			'shipping_address' => 'required',
			'shipping_postcode' => 'required',
			'shipping_city' => 'required',
			'shipping_state' => 'required',
			'shipping_country' => 'required',
		]);

		$user->update($data);

		return redirect()->back();
    }
}
