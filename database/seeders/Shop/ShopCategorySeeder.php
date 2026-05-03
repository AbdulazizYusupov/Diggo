<?php

namespace Database\Seeders\Shop;

use App\Models\ShopCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Elektronika',
                'description' => 'Smartfonlar, quloqchinlar, zaryadlagichlar va boshqa elektron qurilmalar',
                'icon'        => '',
                'color'       => 'indigo',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Najot Ta\'lim',
                'description' => 'Najot Ta\'lim brendli mahsulotlar: futbolka, kepka, daftar va boshqalar',
                'icon'        => '',
                'color'       => 'emerald',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Aksessuarlar',
                'description' => 'Sumkalar, qalam, bloknot, stiker va kundalik aksessuarlar',
                'icon'        => '',
                'color'       => 'sky',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Vaucherlar',
                'description' => 'Kurs chegirmalari, online xizmat voucherlari va maxsus takliflar',
                'icon'        => '',
                'color'       => 'amber',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Uy jihozlari',
                'description' => 'Termoslar, stakanslar, dekorativ buyumlar va uy uchun qulay narsalar',
                'icon'        => '',
                'color'       => 'orange',
                'sort_order'  => 5,
            ],
            [
                'name'        => 'Sport',
                'description' => 'Sport anjomlari, sport kiyimlari va sog\'lom turmush uchun mahsulotlar',
                'icon'        => '',
                'color'       => 'rose',
                'sort_order'  => 6,
            ],
            [
                'name'        => 'Kitoblar',
                'description' => 'O\'quv qo\'llanmalari, rivojlanish kitoblari va badiiy adabiyotlar',
                'icon'        => '',
                'color'       => 'purple',
                'sort_order'  => 7,
            ],
            [
                'name'        => 'Oziq-ovqat',
                'description' => 'Shirinliklar, sneklar, ichimliklar va ovqatlanishga oid mahsulotlar',
                'icon'        => '',
                'color'       => 'teal',
                'sort_order'  => 8,
            ],
        ];

        foreach ($categories as $data) {
            ShopCategory::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                array_merge($data, [
                    'slug'      => Str::slug($data['name']),
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('✅ ShopCategory seeder muvaffaqiyatli bajarildi — ' . count($categories) . ' ta kategoriya qo\'shildi.');
    }
}
