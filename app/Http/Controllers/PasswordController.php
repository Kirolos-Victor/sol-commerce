<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function index(Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		return \Inertia\Inertia::render('Account/Password', [
			'user' => $user
		]);
    }

	public function store(Request $request)
    {
		$user = Auth::user();

		//if (Hash::check($request->oldpassword , $hashedPassword )) {

		$request->validate([
			'password' => 'required',
		]);

		$user->forceFill([
			'password' => Hash::make($request->password),
		])->save();

		return redirect()->back();
    }
}
