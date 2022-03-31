<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

use App\Models\Category;
use App\Models\Cart;
use App\Models\Article;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
		$session_id = $request->session()->getId();
		$cart = Cart::whereNull('order_id')->where('session_id', $session_id)->first();

        // if checkout, add shipping cost
        $totals = [
			'count' => 0,
			'subtotal' => 0,
			'shipping' => 0,
			'total' => 0,
		];
        if ($cart) {
            $cart_variant_ids = $cart->cartItems->pluck('variant_id')->toArray();
            $cart->load(['cartItems', 'cartItems.subscriptionOption', 'cartItems.variant', 'cartItems.variant.subscriptionOptions', 'cartItems.variant.productAddons', 'cartItems.variant.productAddons']);

            $totals = $cart->totals;
            if ($request->route()->getName() === 'checkout.index') {
                $totals['shipping'] += $cart->shipping_price;
                $totals['total'] -= $cart->discount_amount;
                $totals['total'] += $cart->shipping_price;
            }

            $cart = $cart->toArray();

            // loop through addons and remove any already in cart aswell as any duplicates
            foreach ($cart['cart_items'] as $item_key => $item) {
                if ($item['variant']['product_addons']) {
                    foreach ($item['variant']['product_addons'] as $addon_key => $addon) {
                        if (in_array($addon['id'], $cart_variant_ids)) {
                            unset($cart['cart_items'][$item_key]['variant']['product_addons'][$addon_key]);
                        }
                        $cart_variant_ids[] = $addon['id']; // add addon id so it's not shown again
                    }
                }
                $cart['cart_items'][$item_key]['variant']['product_addons'] = array_values($cart['cart_items'][$item_key]['variant']['product_addons']);
            }
        }

        return array_merge(parent::share($request), [
			'route' => $request->route()->getName(),
            'settings' => nova_get_settings(),
            'auth' => [
                'user' => $request->user(),
            ],
			'menu_categories' => Category::whereNull('parent_id')->get(),
			'menu_articles' => Article::limit(4)->latest()->get(),
			'cart' => $cart,
            'totals' => $totals,
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                ];
            },
        ]);
    }
}
