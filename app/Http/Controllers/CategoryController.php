<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Redirect category URL to products page with category filter.
     */
    public function show(string $slug): RedirectResponse
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        return redirect()->route('products.index', ['category' => $category->slug]);
    }
}
