<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;

use Klaviyo\Klaviyo as Klaviyo;
use Klaviyo\Model\ProfileModel as KlaviyoProfile;
use Klaviyo\Model\EventModel as KlaviyoEvent;

use App\Notifications\ContactForm;

class UsersController extends Controller
{
    public function index(Request $request)
    {
		
    }

	public function store(Request $request)
    {
		$data = $request->all();

		$user = User::where('email', $data['email'])->first();

		if (!$user) {
			// create user without a pw
			$user = User::create([
				'first_name' => $data['first_name'],
				'last_name' => !empty($data['last_name']) ? $data['last_name'] : null, 
				'email' => $data['email'],
			]);
		}

		// identify in klav
		$client = new Klaviyo(config('app.klav_private_key'), config('app.klav_public_key'));
		$profile = new KlaviyoProfile(
			[
				'$email' => $user->email,
				'$first_name' => $user->first_name,
				//'$last_name' => $user->last_name,
			]
		);
		$client->publicAPI->identify($profile, true);

		// track filled out profile
		$event = new KlaviyoEvent(
			[
				'event' => 'Filled out Profile',
				'customer_properties' => [
					'$email' => $user->email,
					'$first_name' => $user->first_name,
					//'$last_name' => $user->last_name,
				],
				'properties' => []
			]
		);
		$client->publicAPI->track($event, true);

		// subscribe to newsletter
		$client->lists->addSubscribersToList('RVU3M8', [$profile]);

		// send contact enquiry
		if (!empty($data['subject'])) {
			// $admins = User::where('admin', 1)->get();
			// foreach ($admins as $admin) {
			$admin = new User([
				'first_name' => 'Solcleanse',
				'email' => 'info@solcleanse.com',
			]);
			$admin->notify(new ContactForm($data));
			//}
		}

		return redirect()->back();
    }

	public function paymentMethod(Request $request)
    {
        $user = Auth::user();

		return \Inertia\Inertia::render('Account/PaymentMethod', [
			'user' => $user
		]);
    }
}

