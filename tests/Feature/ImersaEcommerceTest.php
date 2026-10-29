<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImersaEcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * Test homepage loads with PT Imersa brand, categories, and products.
     */
    public function test_homepage_loads_successfully(): void
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('PT IMERSA');
        $response->assertSee('Temukan Koleksi Terbaik Kami');
        $response->assertSee('Kategori Pilihan');
        $response->assertSee('Shopee');
    }

    /**
     * Test product listing, search, category filtering, and sorting.
     */
    public function test_product_catalog_and_filtering(): void
    {
        $user = User::first() ?? User::factory()->create();

        // 1. All products
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Katalog Produk');

        // 2. Filter by category
        $response = $this->actingAs($user)->get('/products?category=kaos');
        $response->assertStatus(200);
        $response->assertSee('Kaos');

        // 3. Search query
        $response = $this->actingAs($user)->get('/products?search=Oversized');
        $response->assertStatus(200);
        $response->assertSee('Heavyweight Oversized');

        // 4. Combined search + category filter
        $response = $this->actingAs($user)->get('/products?search=Oversized&category=kaos');
        $response->assertStatus(200);

        // 5. Empty search state
        $response = $this->actingAs($user)->get('/products?search=NonExistentKeywordXYZ123');
        $response->assertStatus(200);
        $response->assertSee('Produk Tidak Ditemukan');
    }

    /**
     * Test product detail page with reviews, hotlinked media, and direct Shopee order link.
     */
    public function test_product_detail_page(): void
    {
        $user = User::first() ?? User::factory()->create();
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->actingAs($user)->get('/products/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Order di Shopee');
        $response->assertSee($product->shopee_url);
        $response->assertSee('Ulasan Pembeli');
    }

    /**
     * Test category route redirect to catalog.
     */
    public function test_category_route_redirects_to_catalog(): void
    {
        $user = User::first() ?? User::factory()->create();
        $category = Category::first();
        $this->assertNotNull($category);

        $response = $this->actingAs($user)->get('/categories/' . $category->slug);
        $response->assertRedirect(route('products.index', ['category' => $category->slug]));
    }

    /**
     * Test admin authentication and unauthorized redirection.
     */
    public function test_admin_auth_and_protection(): void
    {
        // 1. Guest redirected from dashboard to admin login
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('admin.login'));

        // 2. Admin login page loads
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Login Admin');

        // 3. Login with invalid credentials
        $response = $this->post('/admin/login', [
            'email' => 'admin@imersa.test',
            'password' => 'WrongPassword',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        // 4. Login with valid credentials
        $response = $this->post('/admin/login', [
            'email' => 'admin@imersa.test',
            'password' => 'Admin123!',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        // 5. Authenticated admin can access dashboard
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Produk Katalog');

        // 6. Admin can access resources
        $this->get('/admin/products')->assertStatus(200);
        $this->get('/admin/categories')->assertStatus(200);
        $this->get('/admin/reviews')->assertStatus(200);

        // 7. Logout
        $response = $this->post('/admin/logout');
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    /**
     * Test admin product CRUD with external hotlink URLs.
     */
    public function test_admin_can_create_and_manage_product(): void
    {
        $admin = User::first();
        $category = Category::first();

        // Check create page renders properly
        $createPageResponse = $this->actingAs($admin)->get('/admin/products/create');
        $createPageResponse->assertStatus(200);
        $createPageResponse->assertSee('Tambah Produk Baru');
        $createPageResponse->assertSee('quick_shopee_url');

        // Create
        $response = $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Testing New Product Tee',
            'price' => 199000,
            'description' => 'Bahan katun combed 24s berkualitas tinggi.',
            'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518',
            'shopee_url' => 'https://shopee.co.id/test-product-tee',
            'is_featured' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $product = Product::where('slug', 'testing-new-product-tee')->first();
        $this->assertNotNull($product);
        $this->assertEquals(199000, $product->price);

        // Update
        $response = $this->actingAs($admin)->put('/admin/products/' . $product->id, [
            'category_id' => $category->id,
            'name' => 'Testing Updated Product Tee',
            'price' => 179000,
            'description' => 'Bahan katun combed 24s diupdate.',
            'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518',
            'shopee_url' => 'https://shopee.co.id/test-product-tee-updated',
            'is_featured' => 0,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Testing Updated Product Tee',
            'price' => 179000,
        ]);

        // Delete
        $response = $this->actingAs($admin)->delete('/admin/products/' . $product->id);
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Test safe deletion of category when products are still assigned.
     */
    public function test_category_safe_deletion(): void
    {
        $admin = User::first();
        $category = Category::has('products')->first();
        $this->assertNotNull($category);

        // Attempt delete category that has products -> should fail with error message
        $response = $this->actingAs($admin)->delete('/admin/categories/' . $category->id);
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // Create empty category and delete it -> should succeed
        $emptyCat = Category::create([
            'name' => 'Kategori Sementara',
            'slug' => 'kategori-sementara',
        ]);
        $response = $this->actingAs($admin)->delete('/admin/categories/' . $emptyCat->id);
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $emptyCat->id]);
    }

    /**
     * Test review creation and automatic product rating recalculation.
     */
    public function test_review_creation_and_rating_recalculation(): void
    {
        $admin = User::first();
        $product = Product::create([
            'category_id' => Category::first()->id,
            'name' => 'Produk Rating Test',
            'slug' => 'produk-rating-test',
            'price' => 100000,
            'description' => 'Produk untuk menguji kalkulasi rating otomatis',
            'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518',
            'shopee_url' => 'https://shopee.co.id/test',
            'rating' => 0.00,
        ]);

        $this->assertEquals(0.00, $product->rating);

        // Add 5-star review
        $this->actingAs($admin)->post('/admin/reviews', [
            'product_id' => $product->id,
            'customer_name' => 'Pelanggan Pertama',
            'rating' => 5,
            'review_text' => 'Sangat memuaskan dan recommended!',
            'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde',
            'photo_url' => null,
        ]);

        $product->refresh();
        $this->assertEquals(5.00, (float)$product->rating);

        // Add 3-star review
        $this->actingAs($admin)->post('/admin/reviews', [
            'product_id' => $product->id,
            'customer_name' => 'Pelanggan Kedua',
            'rating' => 3,
            'review_text' => 'Cukup bagus.',
            'avatar_url' => null,
            'photo_url' => null,
        ]);

        $product->refresh();
        // Average of 5 and 3 is 4.00
        $this->assertEquals(4.00, (float)$product->rating);
    }

    /**
     * Test Shopee extractor AJAX endpoint automatically detects and returns price.
     */
    public function test_shopee_extractor_auto_fills_price(): void
    {
        $admin = User::first();

        // 1. Test with direct URL
        $response = $this->actingAs($admin)->postJson('/admin/products/extract-shopee', [
            'shopee_url' => 'https://shopee.co.id/product/40268086/22760826572'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $data = $response->json('data');
        $this->assertNotNull($data['price']);
        $this->assertGreaterThan(0, $data['price']);
        $this->assertNotNull($data['formatted_price']);
        $this->assertTrue($data['price_detected']);

        // 2. Test with share text containing price
        $response2 = $this->actingAs($admin)->postJson('/admin/products/extract-shopee', [
            'shopee_url' => 'Dapatkan produk seharga Rp75.000 di Shopee https://shopee.co.id/test'
        ]);
        $response2->assertStatus(200);
        $this->assertEquals(75000, $response2->json('data.price'));
    }

    /**
     * Test Shopee extractor automatically provides all 6 fields (name, price, image, description, reviews, category)
     * and saves reviews into the database when product is created.
     */
    public function test_shopee_extractor_extracts_all_6_fields_and_saves_reviews(): void
    {
        $admin = User::first();
        $testUrl = 'https://shopee.co.id/Hoodie-Rock-in-Solo-Original-Limited-edition-i.12345.67890';

        $response = $this->actingAs($admin)->postJson('/admin/products/extract-shopee', [
            'shopee_url' => $testUrl,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $data = $response->json('data');

        // Verify all 6 items are present and valid
        // 1. Nama
        $this->assertNotEmpty($data['name']);
        $this->assertEquals('Hoodie Rock In Solo Original Limited Edition', $data['name']);

        // 2. Harga
        $this->assertNotEmpty($data['price']);
        $this->assertGreaterThan(0, $data['price']);

        // 3. Gambar (Product Image)
        $this->assertNotEmpty($data['image_url']);
        $this->assertStringStartsWith('http', $data['image_url']);

        // 4. Deskripsi
        $this->assertNotEmpty($data['description']);

        // 5. Ulasan & Rating
        $this->assertNotEmpty($data['rating']);
        $this->assertGreaterThanOrEqual(4.0, (float) $data['rating']);
        $this->assertNotEmpty($data['rating_count']);
        $this->assertIsArray($data['reviews']);
        $this->assertGreaterThanOrEqual(3, count($data['reviews']));
        $this->assertEquals(5, $data['reviews'][0]['rating']);
        $this->assertNotEmpty($data['reviews'][0]['customer_name']);
        $this->assertNotEmpty($data['reviews'][0]['review_text']);

        // 6. Kategori
        $this->assertNotEmpty($data['category_id']);
        $this->assertEquals('Jaket & Hoodie', $data['category_name']);

        // Test saving product with these extracted reviews
        $saveResponse = $this->actingAs($admin)->post('/admin/products', [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'description' => $data['description'],
            'image_url' => $data['image_url'],
            'shopee_url' => $data['shopee_url'],
            'shopee_reviews' => json_encode($data['reviews']),
        ]);

        $saveResponse->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', $data['name'])->first();
        $this->assertNotNull($product);
        $this->assertGreaterThanOrEqual(3, $product->reviews()->count());
        $this->assertEquals(5.00, (float) $product->rating);
    }

    /**
     * Test direct hotlink extraction and preservation without overwriting.
     */
    public function test_hotlink_extraction_preserves_hotlink_url(): void
    {
        $admin = User::first();
        $hotlink = 'https://down-id.img.susercontent.com/file/id-11134207-7rasd-m3nvokkdysv359';

        $response = $this->actingAs($admin)->postJson('/admin/products/extract-shopee', [
            'shopee_url' => $hotlink,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $data = $response->json('data');

        $this->assertNotEmpty($data['image_url']);
        $this->assertEquals($hotlink, $data['hotlink_image_url']);
        $this->assertTrue($data['is_direct_hotlink'] ?? false);
    }
}
