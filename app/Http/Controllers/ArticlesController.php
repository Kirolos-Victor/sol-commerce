<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Article;
use App\Models\BlogCategory;

class ArticlesController extends Controller
{
    public function index(Request $request, $category = null)
    {
		$user = Auth::user();
		$data = $request->all();

		$featured_article = Article::where('featured', 1)->with('categories')->latest()->first();

		$articles = Article::where('id', '!=', $featured_article->id)->latest()->with('categories');
		$categories = BlogCategory::get();

		// filter category
		if (!empty($category)) {
			$category = BlogCategory::where('url', $category)->first();
			$articles->whereHas('categories', function($query) use($category) {
				$query->where('blog_categories.id', $category->id);
			});
		} 

		$articles = $articles->get();
	
		return \Inertia\Inertia::render('Blog', [
			'articles' => $articles,
			'featured_article' => $featured_article,
			'categories' => $categories,
			'category' => $category,
		]);
    }

	public function article($article, Request $request)
    {
		$user = Auth::user();
		$data = $request->all();

		$categories = BlogCategory::get();
		
		$article = Article::where('url', $article)->first();
		$article->load('categories', 'products');

		$previous_article = Article::where('id', '<', $article->id)->orderBy('id', 'desc')->first();
		$next_article = Article::where('id', '>', $article->id)->orderBy('id', 'desc')->first();

		$related_articles = null;
		if ($article->categories()->count()) {
			$related_articles = BlogCategory::find($article->categories[0]->id)->articles()->where('articles.id', '!=', $article->id)->with('categories')->limit(4)->get();
		}
	
		return \Inertia\Inertia::render('Article', [
			'categories' => $categories,
			'article' => $article,
			'previous_article' => $previous_article,
			'next_article' => $next_article,
			'related_articles' => $related_articles,
		]);
    }
}
