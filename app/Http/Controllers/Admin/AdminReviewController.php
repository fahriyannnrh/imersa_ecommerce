<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of customer reviews.
     */
    public function index(Request $request): View
    {
        $productId = $request->query('product_id');
        $products = Product::orderBy('name')->get();

        $query = Review::with('product');

        if (!empty($productId)) {
            $query->where('product_id', $productId);
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'products', 'productId'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get();

        return view('admin.reviews.create', compact('products'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_text' => ['required', 'string'],
            'avatar_url' => ['nullable', 'url', 'max:2048'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'customer_name.required' => 'Nama pembeli wajib diisi.',
            'rating.required' => 'Rating bintang wajib diisi (1 - 5).',
            'rating.between' => 'Rating harus bernilai antara 1 sampai 5 bintang.',
            'review_text.required' => 'Isi teks ulasan wajib diisi.',
            'avatar_url.url' => 'Format URL avatar tidak valid.',
            'photo_url.url' => 'Format URL foto ulasan tidak valid.',
        ]);

        $review = Review::create($validated);
        $review->product?->recalculateRating();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan pembeli berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review): View
    {
        $products = Product::orderBy('name')->get();

        return view('admin.reviews.edit', compact('review', 'products'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_text' => ['required', 'string'],
            'avatar_url' => ['nullable', 'url', 'max:2048'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'customer_name.required' => 'Nama pembeli wajib diisi.',
            'rating.required' => 'Rating bintang wajib diisi (1 - 5).',
            'rating.between' => 'Rating harus bernilai antara 1 sampai 5 bintang.',
            'review_text.required' => 'Isi teks ulasan wajib diisi.',
            'avatar_url.url' => 'Format URL avatar tidak valid.',
            'photo_url.url' => 'Format URL foto ulasan tidak valid.',
        ]);

        $review->update($validated);
        $review->product?->recalculateRating();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan pembeli berhasil diperbarui.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $product = $review->product;
        $customer = $review->customer_name;
        $review->delete();
        $product?->recalculateRating();

        return redirect()->route('admin.reviews.index')
            ->with('success', "Ulasan dari '{$customer}' berhasil dihapus.");
    }
}
