<?php

namespace Database\Seeders\Shop;

use App\Models\ShopOrder;
use App\Models\ShopProduct;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $students = Student::with('user')->get();
        $products = ShopProduct::all();

        if ($students->isEmpty()) {
            $this->command->warn('⚠️  Studentlar topilmadi. Avval student seeder ishlatib ko\'ring.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('⚠️  Mahsulotlar topilmadi. Avval ShopProductSeeder ishlatib ko\'ring.');
            return;
        }

        $statuses = ['pending', 'pending', 'approved', 'delivered', 'delivered', 'cancelled', 'rejected', 'approved', 'delivered', 'pending'];

        $orders = [
            [
                'status'       => 'delivered',
                'quantity'     => 1,
                'student_note' => 'Iltimos tezroq yetkazib bering',
                'admin_note'   => 'Yetkazildi, qabul qildi',
                'delivery_info'=> 'Ofisda berildi',
            ],
            [
                'status'       => 'delivered',
                'quantity'     => 1,
                'student_note' => null,
                'admin_note'   => 'Muammosiz yetkazildi',
                'delivery_info'=> 'Qo\'lga topshirildi',
            ],
            [
                'status'       => 'approved',
                'quantity'     => 1,
                'student_note' => 'M o\'lchamda bo\'lsa yaxshi',
                'admin_note'   => 'Tasdiqlandi, yetkaziladi',
                'delivery_info'=> null,
            ],
            [
                'status'       => 'pending',
                'quantity'     => 1,
                'student_note' => null,
                'admin_note'   => null,
                'delivery_info'=> null,
            ],
            [
                'status'       => 'pending',
                'quantity'     => 2,
                'student_note' => 'Ikki donasi kerak',
                'admin_note'   => null,
                'delivery_info'=> null,
            ],
            [
                'status'       => 'cancelled',
                'quantity'     => 1,
                'student_note' => 'Tasodifan xarid qildim',
                'admin_note'   => null,
                'delivery_info'=> null,
            ],
            [
                'status'       => 'rejected',
                'quantity'     => 1,
                'student_note' => null,
                'admin_note'   => 'Zaxirada qolmadi, coinlar qaytarildi',
                'delivery_info'=> null,
            ],
            [
                'status'       => 'delivered',
                'quantity'     => 1,
                'student_note' => 'Sovg\'a uchun chiroyli qadoqlang',
                'admin_note'   => 'Chiroyli qadoqlanib berildi',
                'delivery_info'=> 'Ofisda berildi',
            ],
            [
                'status'       => 'approved',
                'quantity'     => 1,
                'student_note' => null,
                'admin_note'   => 'Tasdiqlandi',
                'delivery_info'=> null,
            ],
            [
                'status'       => 'pending',
                'quantity'     => 1,
                'student_note' => 'Imkon qadar tez yetkazing',
                'admin_note'   => null,
                'delivery_info'=> null,
            ],
        ];

        $created = 0;

        foreach ($orders as $index => $orderData) {
            // Studentni va mahsulotni navbatma-navbat tanlaymiz (modulo)
            $student = $students[$index % $students->count()];
            $product = $products[$index % $products->count()];

            $quantity   = $orderData['quantity'];
            $totalCoins = $product->coin_price * $quantity;

            $status       = $orderData['status'];
            $processedBy  = null;
            $processedAt  = null;
            $deliveredAt  = null;

            if (in_array($status, ['approved', 'delivered', 'rejected'])) {
                $processedBy = 1; // Admin user ID (1 — birinchi admin)
                $processedAt = now()->subDays(rand(1, 7));
            }

            if ($status === 'delivered') {
                $deliveredAt = now()->subDays(rand(0, 3));
            }

            ShopOrder::create([
                'order_number'   => ShopOrder::generateOrderNumber(),
                'student_id'     => $student->id,
                'shop_product_id'=> $product->id,
                'quantity'       => $quantity,
                'coin_price'     => $product->coin_price,
                'total_coins'    => $totalCoins,
                'status'         => $status,
                'student_note'   => $orderData['student_note'],
                'admin_note'     => $orderData['admin_note'],
                'delivery_info'  => $orderData['delivery_info'],
                'processed_by'   => $processedBy,
                'processed_at'   => $processedAt,
                'delivered_at'   => $deliveredAt,
                'created_at'     => now()->subDays(rand(1, 30)),
            ]);

            $created++;
        }

        $this->command->info("✅ ShopOrder seeder bajarildi — {$created} ta buyurtma qo'shildi.");
        $this->command->table(
            ['Holat', 'Soni'],
            [
                ['pending',   collect($orders)->where('status', 'pending')->count()],
                ['approved',  collect($orders)->where('status', 'approved')->count()],
                ['delivered', collect($orders)->where('status', 'delivered')->count()],
                ['cancelled', collect($orders)->where('status', 'cancelled')->count()],
                ['rejected',  collect($orders)->where('status', 'rejected')->count()],
            ]
        );
    }
}
