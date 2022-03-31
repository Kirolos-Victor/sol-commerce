<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\Article;

use Whitecube\NovaPage\Pages\Manager;

class HomeController extends Controller
{
    public function index(Request $request)
    {
		$user = Auth::user();
		$data = $request->all();

		$categories = Category::all();

		$articles = Article::latest()->with('categories')->get();

		return \Inertia\Inertia::render('Home', [
			'categories' => $categories,
			'articles' => $articles,
		]);
    }

	public function page( Request $request, $url = null)
    {
		$user = Auth::user();
		$data = $request->all();
        $productNutritionist=Product::with('reviews','reviews.user')->where('title','=','nutritionist consults')->first();


		$page = Page::where('url', $url ? $url : $request->route()->getName())->first();

		if (!$page) {
			return abort(404);
		}

		return \Inertia\Inertia::render('Page', [
			'page' => $page,
            'productNutritionist'=>$productNutritionist

		]);
    }
}
