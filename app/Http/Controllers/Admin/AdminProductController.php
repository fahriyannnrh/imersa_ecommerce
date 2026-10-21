<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ShopeeExtractorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $categories = Category::all();

        $query = Product::with('category');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * AJAX endpoint to extract product details from Shopee URL.
     */
    public function extractShopee(Request $request): JsonResponse
    {
        $request->validate([
            'shopee_url' => ['required', 'string'],
        ], [
            'shopee_url.required' => 'URL Shopee atau teks tautan wajib diisi.',
        ]);

        $userPrice = $request->filled('price') ? (int) $request->input('price') : null;
        $data = ShopeeExtractorService::extract($request->input('shopee_url'), $userPrice);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1-Click Shopee Auto Import: if shopee_url is provided and name or price is missing, auto-extract missing fields
        if ($request->filled('shopee_url') && (!$request->filled('name') || !$request->filled('price'))) {
            $userPrice = $request->filled('price') ? (int) $request->input('price') : null;
            $extracted = ShopeeExtractorService::extract($request->input('shopee_url'), $userPrice);
            if (!$request->filled('name')) {
                $request->merge(['name' => $extracted['name']]);
            }
            if (!$request->filled('category_id')) {
                $request->merge(['category_id' => $extracted['category_id']]);
            }
            if (!$request->filled('price') && !empty($extracted['price'])) {
                $request->merge(['price' => $extracted['price']]);
            }
            if (!$request->filled('description')) {
                $request->merge(['description' => $extracted['description']]);
            }
            if (!$request->filled('image_url') && !$request->hasFile('image_file')) {
                $request->merge(['image_url' => $extracted['image_url']]);
            }
            $request->merge(['shopee_url' => $extracted['shopee_url']]);
        }

        // Handle uploaded image file if provided
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $fileName);
            $request->merge(['image_url' => asset('uploads/products/' . $fileName)]);
        } elseif ($request->filled('image_url') && str_contains($request->input('image_url'), 'susercontent.com')) {
            // Auto download Shopee CDN image to local server
            $localImage = ShopeeExtractorService::downloadAndSaveShopeeImage($request->input('image_url'));
            $request->merge(['image_url' => $localImage]);
        }

        // Ensure Shopee URL is clean direct canonical link
        if ($request->filled('shopee_url')) {
            $request->merge([
                'shopee_url' => ShopeeExtractorService::cleanDirectShopeeUrl($request->input('shopee_url'))
            ]);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'shopee_url' => ['required', 'url', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'image_url.url' => 'Format URL gambar produk tidak valid (harus berupa http:// atau https://).',
            'shopee_url.required' => 'URL Shopee wajib diisi.',
            'shopee_url.url' => 'Format URL Shopee tidak valid (harus berupa http:// atau https://).',
        ]);

        if (empty($validated['image_url'])) {
            $validated['image_url'] = asset('images/placeholder.svg');
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['rating'] = 0.00;

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        // Handle uploaded image file if provided
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $fileName);
            $request->merge(['image_url' => asset('uploads/products/' . $fileName)]);
        } elseif ($request->filled('image_url') && str_contains($request->input('image_url'), 'susercontent.com')) {
            // Auto download Shopee CDN image to local server
            $localImage = ShopeeExtractorService::downloadAndSaveShopeeImage($request->input('image_url'));
            $request->merge(['image_url' => $localImage]);
        }

        // Ensure Shopee URL is clean direct canonical link
        if ($request->filled('shopee_url')) {
            $request->merge([
                'shopee_url' => ShopeeExtractorService::cleanDirectShopeeUrl($request->input('shopee_url'))
            ]);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'shopee_url' => ['required', 'url', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'image_url.url' => 'Format URL gambar produk tidak valid.',
            'shopee_url.required' => 'URL Shopee wajib diisi.',
            'shopee_url.url' => 'Format URL Shopee tidak valid.',
        ]);

        if (empty($validated['image_url'])) {
            $validated['image_url'] = $product->image_url;
        }

        // Update slug if name changed
        if ($validated['name'] !== $product->name) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$productName}' berhasil dihapus.");
    }
}
