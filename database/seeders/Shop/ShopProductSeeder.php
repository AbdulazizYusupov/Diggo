<?php

namespace Database\Seeders\Shop;

use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cats = ShopCategory::pluck('id', 'slug');

        $products = [

            // ── Elektronika (2 ta) ───────────────────────────────────────────
            [
                'category_slug'     => 'elektronika',
                'name'              => 'Bluetooth Quloqchin',
                'short_description' => 'Simsiz, 20 soat batareya, bass sound',
                'description'       => 'Yuqori sifatli bluetooth quloqchin. ANC texnologiyasi, 20 soat batareya quvvati, yengil va qulay dizayn. O\'qish va dam olish uchun ideal.',
                'coin_price'        => 1500,
                'stock_quantity'    => 10,
                'is_unlimited'      => false,
                'brand'             => 'JBL',
                'sku'               => 'ELK-001',
                'max_per_student'   => 1,
                'is_featured'       => true,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'elektronika',
                'name'              => 'USB-C Zaryadlagich (65W)',
                'short_description' => 'Tez zaryadlash, universal, kompakt',
                'description'       => '65W quvvatli tez zaryadlagich. USB-C port, barcha qurilmalar bilan mos keladi. Sayohat uchun qulay kompakt o\'lcham.',
                'coin_price'        => 800,
                'stock_quantity'    => 25,
                'is_unlimited'      => false,
                'brand'             => 'Anker',
                'sku'               => 'ELK-002',
                'max_per_student'   => 2,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],

            // ── Najot Ta'lim (2 ta) ──────────────────────────────────────────
            [
                'category_slug'     => 'najot-talim',
                'name'              => 'Najot Ta\'lim Futbolka',
                'short_description' => 'Premium paxta, brend logotipi, 3 rang',
                'description'       => '100% paxta, yumshoq va nafas oladigan material. Najot Ta\'lim logotipi bilan. S, M, L, XL o\'lchamlarda mavjud. Qora, oq va ko\'k rangda.',
                'coin_price'        => 600,
                'stock_quantity'    => 50,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'NTL-001',
                'max_per_student'   => 3,
                'is_featured'       => true,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'najot-talim',
                'name'              => 'Najot Ta\'lim Kepka',
                'short_description' => 'Stylish, sozlanuvchi, quyoshdan himoya',
                'description'       => 'Najot Ta\'lim brendli kepka. Sozlanuvchi kamar, barcha bosh o\'lchamlariga mos keladi. Yoz uchun quyosh nuri va issiqdan himoya.',
                'coin_price'        => 400,
                'stock_quantity'    => 30,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'NTL-002',
                'max_per_student'   => 2,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],

            // ── Aksessuarlar (2 ta) ──────────────────────────────────────────
            [
                'category_slug'     => 'aksessuarlar',
                'name'              => 'Najot Daftar (A5)',
                'short_description' => '200 varaq, chiziqli, qattiq muqova',
                'description'       => 'A5 formatdagi premium daftar. 200 varaq, chiziqli qog\'oz, qattiq muqova. Yozish va konspekt olish uchun ideal. Har bir sahifada sana va mavzu uchun joy.',
                'coin_price'        => 250,
                'stock_quantity'    => 100,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'AKS-001',
                'max_per_student'   => 5,
                'is_featured'       => false,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'aksessuarlar',
                'name'              => 'Stiker To\'plam',
                'short_description' => '50+ ta stiker, suv o\'tkazmaydigan, laptop uchun',
                'description'       => 'Najot Ta\'lim tematik stikerlar to\'plami. 50+ ta turli xil stiker. Suv o\'tkazmaydigan vinyl material. Noutbuk, planshet, telefon uchun.',
                'coin_price'        => 150,
                'stock_quantity'    => 0,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'AKS-002',
                'max_per_student'   => 3,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],

            // ── Vaucherlar (2 ta) ────────────────────────────────────────────
            [
                'category_slug'     => 'vaucherlar',
                'name'              => 'Kurs Chegirma Voucheri (20%)',
                'short_description' => 'Istalgan kursga 20% chegirma',
                'description'       => 'Najot Ta\'lim\'ning istalgan kursiga 20% chegirma beruvchi voucher. Bir martalik ishlatiladi. Muddati: xarid qilingan kundan 3 oy.',
                'coin_price'        => 2000,
                'stock_quantity'    => 0,
                'is_unlimited'      => true,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'VCH-001',
                'max_per_student'   => 1,
                'is_featured'       => true,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'vaucherlar',
                'name'              => 'Mentor Sessi Voucheri',
                'short_description' => '1 soatlik individual mentor sessiyasi',
                'description'       => 'Tajribali mentor bilan 1 soatlik individual suhbat. Loyiha, karyera yoki texnik savollarga javob. Online yoki ofisda o\'tkaziladi.',
                'coin_price'        => 1200,
                'stock_quantity'    => 0,
                'is_unlimited'      => true,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'VCH-002',
                'max_per_student'   => 2,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],

            // ── Uy jihozlari (2 ta) ──────────────────────────────────────────
            [
                'category_slug'     => 'uy-jihozlari',
                'name'              => 'Najot Termos (500ml)',
                'short_description' => '12 soat issiq, 24 soat sovuq saqlaydi',
                'description'       => 'Premium sifatli termos. 500ml hajm. 12 soat issiq, 24 soat sovuq saqlaydi. Zang o\'tkazmaydigan po\'lat. Najot Ta\'lim logotipi bilan.',
                'coin_price'        => 700,
                'stock_quantity'    => 20,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'UYJ-001',
                'max_per_student'   => 1,
                'is_featured'       => false,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'uy-jihozlari',
                'name'              => 'Najot Mug (Krujka)',
                'short_description' => '350ml, keramik, mikroto\'lqin moslamasi',
                'description'       => 'Keramik krujka. 350ml hajm. Dishwasher va mikroto\'lqin moslamasida ishlatish mumkin. Najot Ta\'lim logotipi bilan. Sovg\'a uchun ideal.',
                'coin_price'        => 300,
                'stock_quantity'    => 40,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'UYJ-002',
                'max_per_student'   => 2,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],

            // ── Sport (2 ta) ─────────────────────────────────────────────────
            [
                'category_slug'     => 'sport',
                'name'              => 'Sport Sumkasi',
                'short_description' => '30L, suv o\'tkazmaydigan, noutbuk joyi bor',
                'description'       => 'Multifunksional sport sumkasi. 30 litr hajm. Suv o\'tkazmaydigan material. Noutbuk uchun alohida bo\'lim. Sport va kundalik foydalanish uchun.',
                'coin_price'        => 900,
                'stock_quantity'    => 15,
                'is_unlimited'      => false,
                'brand'             => 'Najot Ta\'lim',
                'sku'               => 'SPR-001',
                'max_per_student'   => 1,
                'is_featured'       => false,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'sport',
                'name'              => 'Fitnes Daftarcha',
                'short_description' => 'Mashg\'ulot va ovqat kundaligi, 90 kun',
                'description'       => '90 kunlik fitnes va ovqatlanish kundaligi. Har kun uchun mashg\'ulot, oziq-ovqat va maqsad ro\'yxati. A5 format, qattiq muqova.',
                'coin_price'        => 200,
                'stock_quantity'    => 60,
                'is_unlimited'      => false,
                'brand'             => null,
                'sku'               => 'SPR-002',
                'max_per_student'   => 2,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],

            // ── Kitoblar (1 ta) ──────────────────────────────────────────────
            [
                'category_slug'     => 'kitoblar',
                'name'              => 'Clean Code (O\'zbek tilida)',
                'short_description' => 'Robert C. Martin — dasturchilar uchun klassika',
                'description'       => 'Robert C. Martin\'ning "Clean Code" kitobi O\'zbek tilida. Yaxshi kod yozish tamoyillari, refaktoring va professional dasturlash usullari. 464 sahifa.',
                'coin_price'        => 500,
                'stock_quantity'    => 12,
                'is_unlimited'      => false,
                'brand'             => null,
                'sku'               => 'KTB-001',
                'max_per_student'   => 1,
                'is_featured'       => true,
                'sort_order'        => 1,
            ],

            // ── Oziq-ovqat (2 ta) ────────────────────────────────────────────
            [
                'category_slug'     => 'oziq-ovqat',
                'name'              => 'Shokolad To\'plam',
                'short_description' => 'Premium shokoladlar assortimenti, 200g',
                'description'       => 'Turli xil premium shokoladlar to\'plami. 200 gramm. Chiroyli sovg\'a qutisida. Belgiya va Shveytsariya shokoladlari aralashmasi.',
                'coin_price'        => 350,
                'stock_quantity'    => 30,
                'is_unlimited'      => false,
                'brand'             => null,
                'sku'               => 'OZQ-001',
                'max_per_student'   => 3,
                'is_featured'       => false,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'oziq-ovqat',
                'name'              => 'Energetik Ichimlik (6 dona)',
                'short_description' => 'Kofein va vitaminlar, diqqatni kuchaytiradi',
                'description'       => 'Talabalar uchun maxsus energetik ichimlik. 6 donali paket. Tabiiy kofein, B vitaminlari va elektrolit. Imtihon va intensiv o\'qish davrida ideal.',
                'coin_price'        => 280,
                'stock_quantity'    => 45,
                'is_unlimited'      => false,
                'brand'             => null,
                'sku'               => 'OZQ-002',
                'max_per_student'   => 5,
                'is_featured'       => false,
                'sort_order'        => 2,
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($products as $data) {
            $categorySlug = $data['category_slug'];
            unset($data['category_slug']);

            // Kategoriyani slug orqali topamiz
            $categoryId = $cats[$categorySlug] ?? null;

            if (!$categoryId) {
                $this->command->warn("⚠️  '{$categorySlug}' kategoriyasi topilmadi, o'tkazib yuborildi.");
                $skipped++;
                continue;
            }

            ShopProduct::updateOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, [
                    'shop_category_id' => $categoryId,
                    'slug'             => Str::slug($data['name']),
                    'image'            => null,
                    'images'           => null,
                    'is_active'        => true,
                    'total_sold'       => 0,
                ])
            );

            $created++;
        }

        $this->command->info("✅ ShopProduct seeder bajarildi — {$created} ta mahsulot qo'shildi" . ($skipped ? ", {$skipped} ta o'tkazib yuborildi." : "."));
    }
}
