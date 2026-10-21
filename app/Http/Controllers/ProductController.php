<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display product catalog with search, category filtering, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'latest');

        $categories = Category::withCount('products')->get();
        $selectedCategory = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;

        $query = Product::with(['category', 'reviews']);

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Category filter
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }

        // Sorting
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(9)->withQueryString();

        return view('products.index', compact('products', 'categories', 'selectedCategory', 'search', 'sort'));
    }

    /**
     * Display product detail page with external hotlinked media, reviews, and Shopee direct link.
     */
    public function show(string $slug): View
    {
        $product = Product::with(['category', 'reviews' => function ($query) {
            $query->latest();
        }])->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
