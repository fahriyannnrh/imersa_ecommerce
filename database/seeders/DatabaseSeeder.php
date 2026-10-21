<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::updateOrCreate(
            ['email' => 'admin@imersa.test'],
            [
                'name' => 'Admin PT Imersa',
                'role' => 'admin',
                'password' => Hash::make('Admin123!'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Categories
        $categoriesData = [
            [
                'name' => 'Kaos',
                'slug' => 'kaos',
                'description' => 'Koleksi kaos santai, oversized tee, dan t-shirt basic berkualitas tinggi dengan bahan katun combed yang sejuk dan nyaman.',
                'icon' => 'tshirt',
            ],
            [
                'name' => 'Kemeja',
                'slug' => 'kemeja',
                'description' => 'Kemeja kasual, kemeja flannel, linen, dan kemeja kerja dengan potongan modern dan rapi.',
                'icon' => 'shirt',
            ],
            [
                'name' => 'Celana',
                'slug' => 'celana',
                'description' => 'Celana panjang chino, denim jeans, celana kargo, dan celana santai untuk berbagai gaya.',
                'icon' => 'pants',
            ],
            [
                'name' => 'Jaket & Hoodie',
                'slug' => 'jaket-hoodie',
                'description' => 'Outerwear stylish, hoodie fleece hangat, bomber jacket, dan windbreaker tahan cuaca.',
                'icon' => 'jacket',
            ],
            [
                'name' => 'Aksesoris',
                'slug' => 'aksesoris',
                'description' => 'Aksesoris pelengkap gaya seperti topi snapback, tote bag kanvas, dan ikat pinggang.',
                'icon' => 'accessories',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 3. Seed Products with External Hotlink Media URLs & Shopee Official Store URLs
        $productsData = [
            [
                'category_slug' => 'kaos',
                'name' => 'Heavyweight Oversized T-Shirt Black',
                'slug' => 'heavyweight-oversized-t-shirt-black',
                'price' => 149000,
                'description' => "Kaos oversized berwarna hitam pekat dengan material 100% Heavyweight Cotton Combed 24s. Memiliki daya serap keringat tinggi, tekstur kain lembut, jahitan rantai ganda pada leher dan pundak yang tahan lama, serta potongan boxy oversized kekinian yang nyaman dipakai beraktivitas sehari-hari.\n\nDetail Spesifikasi:\n- Material: 100% Cotton Combed 24s Premium\n- Fitting: Boxy Oversized Fit\n- Rib Leher Tebal Anti Melar\n- Sablon: High-Density Plastisol\n- Unisex (Cocok Pria & Wanita)",
                'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Heavyweight-Oversized-T-Shirt-Black-i.123456789.987654321',
                'is_featured' => true,
                'rating' => 4.9,
                'reviews' => [
                    [
                        'customer_name' => 'Dimas Pratama',
                        'rating' => 5,
                        'review_text' => 'Bahan kaosnya tebel tapi gak gerah sama sekali. Fitting oversized bener-bener pas di badan, jahitan sangat rapi. Bakal langganan terus di PT Imersa!',
                        'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'customer_name' => 'Rian Hidayat',
                        'rating' => 5,
                        'review_text' => 'Kualitas produk melebihi ekspektasi untuk harga segini. Diorder via Shopee cepet banget nyampenya, packaging rapi dan wangi.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'customer_name' => 'Anisa Rahmawati',
                        'rating' => 4,
                        'review_text' => 'Suka banget sama potongannya, bahannya jatuh dan adem. Sedikit kebesaran di saya tapi tetep estetik dipaduin celana kulot.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => null,
                    ],
                ],
            ],
            [
                'category_slug' => 'kaos',
                'name' => 'Classic Pocket Tee Vintage White',
                'slug' => 'classic-pocket-tee-vintage-white',
                'price' => 129000,
                'description' => "Kaos putih klasik beraksen saku dada fungsional. Menggunakan katun organik 30s premium yang sangat lembut dan nyaman di iklim tropis. Warna broken white memberikan kesan vintage elegan yang mudah dipadukan dengan berbagai celana.",
                'image_url' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Classic-Pocket-Tee-Vintage-White-i.123456789.987654322',
                'is_featured' => true,
                'rating' => 4.8,
                'reviews' => [
                    [
                        'customer_name' => 'Budi Santoso',
                        'rating' => 5,
                        'review_text' => 'Warna putihnya bagus, bukan putih ngejreng. Pas banget dipake buat nongkrong santai sore-sore.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => null,
                    ],
                ],
            ],
            [
                'category_slug' => 'kaos',
                'name' => 'Urban Graphic Tee Acid Wash',
                'slug' => 'urban-graphic-tee-acid-wash',
                'price' => 159000,
                'description' => "Kaos streetwear berteknik acid wash dengan grafis tipografi futuristik khas PT Imersa. Tekstur unik dengan nuansa retro grunge yang otentik.",
                'image_url' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Urban-Graphic-Tee-Acid-Wash-i.123456789.987654323',
                'is_featured' => true,
                'rating' => 4.9,
                'reviews' => [
                    [
                        'customer_name' => 'Fajar Nugraha',
                        'rating' => 5,
                        'review_text' => 'Efek acid wash-nya keren parah, sablonan detail dan ga gampang retak waktu dicuci berkali-kali.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=600&q=80',
                    ],
                ],
            ],
            [
                'category_slug' => 'kemeja',
                'name' => 'Casual Linen Long Sleeve Shirt Olive',
                'slug' => 'casual-linen-long-sleeve-shirt-olive',
                'price' => 249000,
                'description' => "Kemeja lengan panjang berbahan campuran linen premium dan katun alami. Ringan, bernapas (breathable), dan memberikan siluet kasual yang berkelas untuk acara semi-formal maupun liburan santai.",
                'image_url' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Casual-Linen-Long-Sleeve-Shirt-Olive-i.123456789.987654324',
                'is_featured' => true,
                'rating' => 4.9,
                'reviews' => [
                    [
                        'customer_name' => 'Rangga Wijaya',
                        'rating' => 5,
                        'review_text' => 'Bahan linennya beneran premium, dingin waktu dipake siang-siang. Jahitannya rapi banget!',
                        'avatar_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => null,
                    ],
                ],
            ],
            [
                'category_slug' => 'kemeja',
                'name' => 'Retro Flannel Plaid Shirt Navy Brown',
                'slug' => 'retro-flannel-plaid-shirt-navy-brown',
                'price' => 229000,
                'description' => "Kemeja flannel kotak-kotak dengan wol katun bertekstur lembut. Menghadirkan kenyamanan ekstra dan tampilan timeless yang dapat dijadikan kemeja maupun outerwear.",
                'image_url' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Retro-Flannel-Plaid-Shirt-Navy-Brown-i.123456789.987654325',
                'is_featured' => false,
                'rating' => 4.7,
                'reviews' => [],
            ],
            [
                'category_slug' => 'celana',
                'name' => 'Slim Fit Chino Pants Khaki Tan',
                'slug' => 'slim-fit-chino-pants-khaki-tan',
                'price' => 219000,
                'description' => "Celana panjang chino dengan material Cotton Twill Stretch yang fleksibel saat bergerak. Dilengkapi resleting YKK berkualitas tinggi, saku samping dalam, dan potongan slim fit yang menawan.",
                'image_url' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Slim-Fit-Chino-Pants-Khaki-Tan-i.123456789.987654326',
                'is_featured' => true,
                'rating' => 4.9,
                'reviews' => [
                    [
                        'customer_name' => 'Eko Prasetyo',
                        'rating' => 5,
                        'review_text' => 'Celananya stretch dan enak banget dipake kerja seharian. Ukuran pinggang akurat sesuai size chart.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=600&q=80',
                    ],
                ],
            ],
            [
                'category_slug' => 'celana',
                'name' => 'Straight Leg Selvedge Denim Light Blue',
                'slug' => 'straight-leg-selvedge-denim-light-blue',
                'price' => 319000,
                'description' => "Denim 14oz non-stretch dengan warna light wash vintage. Mengusung potongan straight leg yang membuat siluet kaki terlihat proporsional dan gagah.",
                'image_url' => 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Straight-Leg-Selvedge-Denim-Light-Blue-i.123456789.987654327',
                'is_featured' => true,
                'rating' => 4.8,
                'reviews' => [],
            ],
            [
                'category_slug' => 'jaket-hoodie',
                'name' => 'Essential Pullover Hoodie Heather Grey',
                'slug' => 'essential-pullover-hoodie-heather-grey',
                'price' => 289000,
                'description' => "Hoodie pullover berbahan Cotton Fleece 330gsm tebal dan lembut di bagian dalam. Tali tudung bulat kokoh, saku kanguru luas, dan rib elastis di pergelangan tangan dan pinggang.",
                'image_url' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Essential-Pullover-Hoodie-Heather-Grey-i.123456789.987654328',
                'is_featured' => true,
                'rating' => 5.0,
                'reviews' => [
                    [
                        'customer_name' => 'Kevin Sanjaya',
                        'rating' => 5,
                        'review_text' => 'Hoodie paling nyaman yang pernah saya beli. Tebal, hangat, tapi ga bikin gerah. Warna abunya mewah.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => null,
                    ],
                ],
            ],
            [
                'category_slug' => 'jaket-hoodie',
                'name' => 'Trucker Denim Jacket Washed Black',
                'slug' => 'trucker-denim-jacket-washed-black',
                'price' => 349000,
                'description' => "Jaket trucker denim berdesain klasik dengan washed tone hitam keabu-abuan. Kancing tembaga antik dan saku ganda fungsional di bagian dada.",
                'image_url' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Trucker-Denim-Jacket-Washed-Black-i.123456789.987654329',
                'is_featured' => false,
                'rating' => 4.8,
                'reviews' => [],
            ],
            [
                'category_slug' => 'aksesoris',
                'name' => 'Heritage Washed Cotton Baseball Cap',
                'slug' => 'heritage-washed-cotton-baseball-cap',
                'price' => 89000,
                'description' => "Topi baseball dengan teknik washed cotton yang lentur dan nyaman. Dilengkapi pengatur gesper logam di bagian belakang untuk menyesuaikan lingkar kepala.",
                'image_url' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Heritage-Washed-Cotton-Baseball-Cap-i.123456789.987654330',
                'is_featured' => true,
                'rating' => 4.9,
                'reviews' => [
                    [
                        'customer_name' => 'Reza Pahlevi',
                        'rating' => 5,
                        'review_text' => 'Bentuk topinya pas di kepala, ga kekecilan. Jahitan bordir logo PT Imersa sangat presisi.',
                        'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                        'photo_url' => null,
                    ],
                ],
            ],
            [
                'category_slug' => 'aksesoris',
                'name' => 'Heavy Duty Canvas Tote Bag Natural',
                'slug' => 'heavy-duty-canvas-tote-bag-natural',
                'price' => 99000,
                'description' => "Tote bag kanvas tebal 14oz berkapasitas muat laptop 15 inci. Dilengkapi saku dalam beresleting untuk menyimpan kunci, kartu, dan smartphone.",
                'image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Heavy-Duty-Canvas-Tote-Bag-Natural-i.123456789.987654331',
                'is_featured' => false,
                'rating' => 4.7,
                'reviews' => [],
            ],
            [
                'category_slug' => 'kaos',
                'name' => 'Waffle Knit Long Sleeve T-Shirt Charcoal',
                'slug' => 'waffle-knit-long-sleeve-t-shirt-charcoal',
                'price' => 169000,
                'description' => "Kaos lengan panjang dengan rajutan waffle bertekstur thermal yang estetik. Hangat saat cuaca dingin dan tetap sejuk di ruangan ber-AC.",
                'image_url' => 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?auto=format&fit=crop&w=800&q=80',
                'shopee_url' => 'https://shopee.co.id/PT-Imersa-Waffle-Knit-Long-Sleeve-T-Shirt-Charcoal-i.123456789.987654332',
                'is_featured' => false,
                'rating' => 4.8,
                'reviews' => [],
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_slug']];
            $reviews = $pData['reviews'] ?? [];

            unset($pData['category_slug'], $pData['reviews']);
            $pData['category_id'] = $cat->id;
            $pData['shopee_url'] = 'https://shopee.co.id/search?keyword=' . urlencode($pData['name']);

            $product = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );

            // Seed reviews
            foreach ($reviews as $rev) {
                $rev['product_id'] = $product->id;
                Review::create($rev);
            }

            // Recalculate average rating
            $product->recalculateRating();
        }
    }
}
