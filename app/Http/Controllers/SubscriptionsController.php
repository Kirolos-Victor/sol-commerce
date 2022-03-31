<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Models\SubscriptionProfile;
use App\Models\BlockedDate;

class SubscriptionsController extends Controller
{
    public function index(Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		$subscriptions = $user->subscriptionProfiles()->with('variant')->get();

		return \Inertia\Inertia::render('Account/Subscriptions', [
			'user' => $user,
			'subscriptions' => $subscriptions
		]);
    }

	public function update(SubscriptionProfile $subscription, Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		// pause subscription
		if ($data['delivery_date']) {
			$delivery_date = Carbon::parse($data['delivery_date']); 

			if (!empty($data['pause'])) {
				$subscription->update([
					'paused_count' => $subscription->paused_count ? $subscription->paused_count + 1 : 1,
				]);
			}

			$next_order = $subscription->nextOrder;
			if ($next_order) {
				$next_order->update(['delivery_date' => $delivery_date]);
			}
		}

		return redirect()->back();
    }

	public function show(SubscriptionProfile $subscription, Request $request)
    {
		$data = $request->all();
		$user = Auth::user();

		$subscription->load('location', 'variant', 'orders', 'nextOrder');

		$blocked_dates_array = [strtotime('now')*1000, strtotime('tomorrow')*1000, strtotime('+2 days')*1000, strtotime('+3 days')*1000, strtotime('+4 days')*1000];
		$location_id = $subscription->location->id;

		// get blocked dates for location
		$blocked_dates = BlockedDate::whereNull('product_id')->whereNull('category_id')->where(function($query) use ($location_id) {
			$query->whereNull('location_id');
			$query->orWhere('location_id', $location_id);
		})->get();

		foreach ($blocked_dates as $blocked_date) {
			$array = explode("\n", $blocked_date->dates); foreach ($array as $key => $value) { $array[$key] = strtotime($value) * 1000; }
			$blocked_dates_array = array_merge(array_diff($blocked_dates_array, $array), array_diff($array, $blocked_dates_array));
		}

		// check blocked off dates for perishable products
		if ($subscription->variant->product->perishable) {

			// add blocked dates for products
			$blocked_dates = $subscription->variant->product->blockedDates()->where(function($query) use ($location_id) {
				$query->whereNull('location_id');
				$query->orWhere('location_id', $location_id);
			})->get();
			foreach ($blocked_dates as $blocked_date) {
				$array = explode("\n", $blocked_date->dates); foreach ($array as $key => $value) { $array[$key] = strtotime($value) * 1000; }
				$blocked_dates_array = array_merge(array_diff($blocked_dates_array, $array), array_diff($array, $blocked_dates_array));
			}

			// add blocked dates for categories
			$categories = $subscription->variant->product->categories;
			if ($categories->count()) {
				$blocked_dates = BlockedDate::whereIn('category_id', $categories->pluck('id'))->where(function($query) use ($location_id) {
					$query->whereNull('location_id');
					$query->orWhere('location_id', $location_id);
				})->get();
				foreach ($blocked_dates as $blocked_date) {
					$array = explode("\n", $blocked_date->dates); foreach ($array as $key => $value) { $array[$key] = strtotime($value) * 1000; }
					$blocked_dates_array = array_merge(array_diff($blocked_dates_array, $array), array_diff($array, $blocked_dates_array));
				}
			}
		}

		return \Inertia\Inertia::render('Account/Subscription', [
			'subscription' => $subscription,
			'blocked_dates' => $blocked_dates_array,
		]);
    }
}
