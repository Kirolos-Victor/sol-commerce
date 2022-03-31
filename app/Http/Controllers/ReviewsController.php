<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;

class ReviewsController extends Controller
{
    public function show($order_id, Request $request)
    {
		$data = $request->all();

		$order = Order::find($order_id)->load('orderItems');

		return \Inertia\Inertia::render('Review', [
			'order' => $order,
		]);
    }

	public function store($order_id, Request $request)
    {
		$data = $request->all();
		$order = Order::find($order_id);

		$request->validate([
			'rating' => ['required'],
			'review' => ['required'],
		]);



		return redirect()->back();
    }
    public function nutritionist(){
        return \Inertia\Inertia::render('NutritionistReview', [
        ]);
    }
    public function nutritionistSubmit(Request $request){

        $request->validate([
            'name'=>['required'],
            'email'=>['required','email'],
            'rating' => ['required'],
            'review' => ['required'],
        ]);
        $review=Review::create([
           'backup_name'=>$request['name'],
           'backup_email'=>$request['email'],
           'rating'=>$request['rating'],
            'review'=>$request['review'],
            'created_at'=>Carbon::now()
        ]);
        $nutritionistProduct=Product::where('title','=','nutritionist consults')->first();
        $nutritionistProduct->reviews()->attach($review->id);
        return redirect()->back()->with('success','Thank you for reviewing our nutritionist consultation.');

    }
}
