<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Models\Cart;
use App\Models\Variant;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

	public function location(Request $request)
    {
		// validate
		$request->validate([
			'location_id' => ['required'],
		]);

		$data = $request->all();
		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->whereNull('order_id')->first();

		if (!$cart) {
			$cart = Cart::create([
				'session_id' => $session_id,
				'location_id' => $data['location_id'],
				'non_perishable' => $data['non_perishable']
			]);
			$cart->fillCartWithUser();

			$data['session_id'] = $session_id;
            $cart->update($data); 
		} else {
			$cart->update([
				'location_id' => $data['location_id'],
				'non_perishable' => $data['non_perishable']
			]);
		}

		return response()->json($cart);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$data = $request->all();

		$request->validate([
			'variant_id' => ['required'],
			'qty' => ['required'],
		]);

		$variant = Variant::find($data['variant_id']);
		if ($variant->product->custom_options) {
			$request->validate([
				'custom_options' => ['array'],
				'custom_options.*' => ['required_with:custom_options'],
			]);
		}

		$session_id = $request->session()->getId();
		$cart = Cart::where('session_id', $session_id)->whereNull('order_id')->first();

		if (!$cart) {
			$cart = Cart::create([
				'session_id' => $session_id,
				'location_id' => 1 // default to gc
			]);
			$cart->fillCartWithUser();
		}

		$cart_item = $cart->cartItems()
			->where('variant_id', $data['variant_id'])
			->where('subscription_option_id', $data['subscription_option_id'])
			->first();

		if ($cart_item) {
			if ($data['mode'] == 'add') {
				$cart_item->update(['qty' => $cart_item->qty + $data['qty']]);
			} else {
				if ($data['qty'] == 0) {
					$cart_item->delete();
				} else {
					$cart_item->update(['qty' => $data['qty']]);
				}
			}
		} else {
			$cart->cartItems()->create([
				'variant_id' => $data['variant_id'],
				'subscription_option_id' => $data['subscription_option_id'],
				'qty' => $data['qty'],
				'custom_options' => !empty($data['custom_options']) ? $data['custom_options'] : null
			]);
		}

		return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
