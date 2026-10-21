<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with summary metrics.
     */
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalReviews = Review::count();
        $averageRating = Review::count() > 0 ? round(Review::avg('rating'), 1) : 0;

        $recentProducts = Product::with('category')->latest()->take(5)->get();
        $recentReviews = Review::with('product')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalReviews',
            'averageRating',
            'recentProducts',
            'recentReviews'
        ));
    }
}
