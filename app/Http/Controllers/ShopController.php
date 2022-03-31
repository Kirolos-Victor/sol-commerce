<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Product;
use App\Models\Category;

use Carbon\Carbon;

class ShopController extends Controller
{
    public function index(Request $request, $category = null)
    {
		$user = Auth::user();
		$data = $request->all();

		if ($category && $category === 'nutritionist-consults') {
			return redirect('clinical-nutritionist-consults');
		}

		$now = Carbon::now();
		$products = Product::where('visible', 1)->has('variants')->with('variants', 'reviews')->where(function($query) use ($now) {
			$query->where(function($query) use ($now) {
				$query->orWhere('start_date', '<=', $now)->orWhereNull('start_date');
			});
			$query->where(function($query) use ($now) {
				$query->orWhere('end_date', '>=', $now)->orWhereNull('end_date');
			});
		});

		$categories = Category::whereNull('parent_id')->with('subcategories')->get();

		// filter title
		if (!empty($data['search'])) {
			$products->where(function($query) use($data) {
				$query->orWhere('title', 'like', '%'.$data['search'].'%');
				$query->orWhere('subtitle', 'like', '%'.$data['search'].'%');
				$query->orWhere('description', 'like', '%'.$data['search'].'%');
				$query->orWhere('content', 'like', '%'.$data['search'].'%');
			});
		}

		// filter category
		if (!empty($category)) {
			$category = Category::where('url', $category)->first();
			$products->whereHas('categories', function($query) use($category) {
				$query->where('categories.id', $category->id);
			});
		}

		$products = $products->ordered()->get();

		return \Inertia\Inertia::render('Shop', [
			'search' => isset($data['search']) ? $data['search'] : '',
			'products' => $products,
			'categories' => $categories,
			'category' => $category,
		]);
    }

    public function product($page, Request $request)
    {
		$now = Carbon::now();
		$user = Auth::user();

        $product = Product::where('url', $page);

		if (!$user || ($user && !$user->admin)) {
			$product->where(function($query) use ($now) {
				$query->where(function($query) use ($now) {
					$query->where('start_date', '<=', $now)->orWhereNull('start_date');
				});
				$query->where(function($query) use ($now) {
					$query->where('end_date', '>=', $now)->orWhereNull('end_date');
				});
			});
		}

		$product = $product->first();

		if (!$product) {
			abort(404);
		}

		$product->load(['variants' => function($query) {
			$query->ordered();
		}, 'variants.subscriptionOptions', 'categories', 'reviews','reviews.user', 'productItems']);


		return \Inertia\Inertia::render('Product', [
			'product' => $product,
			'recommended_products' => $product->productRecommendations()->with('variants')->get()
		]);
    }
}
