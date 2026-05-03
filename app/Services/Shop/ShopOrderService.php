<?php

namespace App\Services\Shop;

use App\Models\CoinTransaction;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ShopOrder\ShopOrderCreatedNotification;
use App\Notifications\ShopOrder\ShopOrderStatusChangedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use RuntimeException;

class ShopOrderService
{
    public function placeOrder(ShopProduct $product, Student $student, int $quantity, ?string $studentNote = null): ShopOrder
    {
        if ($quantity < 1) {
            throw new RuntimeException('Buyurtma miqdori kamida 1 bo\'lishi kerak.');
        }

        $order = DB::transaction(function () use ($product, $student, $quantity, $studentNote) {
            $product = ShopProduct::lockForUpdate()->findOrFail($product->id);
            $student = Student::lockForUpdate()->findOrFail($student->id);

            if (!$product->is_active || !$product->is_in_stock) {
                throw new RuntimeException('Mahsulot mavjud emas yoki faol emas.');
            }

            $alreadyBought = ShopOrder::where('student_id', $student->id)
                ->where('shop_product_id', $product->id)
                ->whereIn('status', [ShopOrder::STATUS_PENDING, ShopOrder::STATUS_APPROVED, ShopOrder::STATUS_DELIVERED])
                ->lockForUpdate()
                ->sum('quantity');

            if ($product->max_per_student !== null && $alreadyBought + $quantity > $product->max_per_student) {
                throw new RuntimeException('Siz bu mahsulotdan maksimal miqdor sotib oldingiz.');
            }

            $totalCoins = $product->coin_price * $quantity;

            if ($student->coins < $totalCoins) {
                throw new RuntimeException('Coiningiz yetarli emas.');
            }

            if (!$product->is_unlimited && $product->stock_quantity < $quantity) {
                throw new RuntimeException('Zaxirada yetarli mahsulot yo\'q.');
            }

            $student->coins -= $totalCoins;
            $student->saveQuietly();

            if (!$product->is_unlimited) {
                $product->stock_quantity -= $quantity;
            }
            $product->total_sold += $quantity;
            $product->saveQuietly();

            $order = ShopOrder::create([
                'student_id'      => $student->id,
                'shop_product_id' => $product->id,
                'quantity'        => $quantity,
                'coin_price'      => $product->coin_price,
                'total_coins'     => $totalCoins,
                'student_note'    => $studentNote,
                'status'          => ShopOrder::STATUS_PENDING,
            ]);

            $this->recordCoinTransaction(
                student: $student,
                coins: -$totalCoins,
                type: 'purchase',
                description: "Buyurtma #{$order->order_number} uchun coin to'lovi",
                actorUserId: null,
            );

            return $order;
        });

        Notification::send($this->getAdminReviewers(), new ShopOrderCreatedNotification($order));

        return $order;
    }

    public function approveOrder(ShopOrder $order, User $admin, ?string $adminNote = null): ShopOrder
    {
        if ($order->status !== ShopOrder::STATUS_PENDING) {
            throw new RuntimeException('Faqat kutilayotgan buyurtmalarni tasdiqlash mumkin.');
        }

        DB::transaction(function () use ($order, $admin, $adminNote) {
            $order->update([
                'status'       => ShopOrder::STATUS_APPROVED,
                'admin_note'   => $adminNote,
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);
        });

        $order->refresh();
        $this->notifyStudentStatusChanged($order);

        return $order;
    }

    public function rejectOrder(ShopOrder $order, User $admin, string $adminNote): ShopOrder
    {
        if (!in_array($order->status, [ShopOrder::STATUS_PENDING, ShopOrder::STATUS_APPROVED], true)) {
            throw new RuntimeException('Bu buyurtmani rad etib bo\'lmaydi.');
        }

        DB::transaction(function () use ($order, $admin, $adminNote) {
            $student = Student::lockForUpdate()->findOrFail($order->student_id);
            $product = ShopProduct::lockForUpdate()->findOrFail($order->shop_product_id);

            $student->coins += $order->total_coins;
            $student->saveQuietly();

            if (!$product->is_unlimited) {
                $product->stock_quantity += $order->quantity;
                $product->total_sold = max(0, $product->total_sold - $order->quantity);
                $product->saveQuietly();
            }

            $this->recordCoinTransaction(
                student: $student,
                coins: $order->total_coins,
                type: 'refund',
                description: "Do'kon buyurtmasi rad etildi: #{$order->order_number}",
                actorUserId: $admin->id,
            );

            $order->update([
                'status'       => ShopOrder::STATUS_REJECTED,
                'admin_note'   => $adminNote,
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);
        });

        $order->refresh();
        $this->notifyStudentStatusChanged($order);

        return $order;
    }

    public function deliverOrder(ShopOrder $order, User $admin, ?string $adminNote = null, ?string $deliveryInfo = null): ShopOrder
    {
        if ($order->status !== ShopOrder::STATUS_APPROVED) {
            throw new RuntimeException('Faqat tasdiqlangan buyurtmalarni yetkazildi deb belgilash mumkin.');
        }

        $order->update([
            'status'        => ShopOrder::STATUS_DELIVERED,
            'delivery_info' => $deliveryInfo,
            'admin_note'    => $adminNote,
            'processed_by'  => $admin->id,
            'delivered_at'  => now(),
        ]);

        return $order;
    }

    public function cancelOrder(ShopOrder $order, Student $student): ShopOrder
    {
        if ($order->student_id !== $student->id) {
            throw new RuntimeException('Siz bu buyurtmani bekor qila olmaysiz.');
        }

        if ($order->status !== ShopOrder::STATUS_PENDING) {
            throw new RuntimeException('Faqat kutilayotgan buyurtmalarni bekor qilish mumkin.');
        }

        DB::transaction(function () use ($order, $student) {
            $student = Student::lockForUpdate()->findOrFail($student->id);
            $order->load('product');

            $student->coins += $order->total_coins;
            $student->saveQuietly();

            if ($order->product && !$order->product->is_unlimited) {
                $product = ShopProduct::lockForUpdate()->findOrFail($order->product->id);
                $product->stock_quantity += $order->quantity;
                $product->total_sold = max(0, $product->total_sold - $order->quantity);
                $product->saveQuietly();
            }

            $this->recordCoinTransaction(
                student: $student,
                coins: $order->total_coins,
                type: 'refund',
                description: "Do'kon buyurtmasi bekor qilindi: #{$order->order_number}",
                actorUserId: null,
            );

            $order->update(['status' => ShopOrder::STATUS_CANCELLED]);
        });

        return $order;
    }

    private function recordCoinTransaction(Student $student, int $coins, string $type, string $description, ?int $actorUserId): CoinTransaction
    {
        return CoinTransaction::create([
            'student_id'       => $student->id,
            'user_id'          => $actorUserId,
            'activity_id'      => null,
            'coins'            => $coins,
            'type'             => $type,
            'description'      => $description,
            'balance_after'    => $student->coins,
            'transaction_date' => now()->toDateString(),
            'expiry_date'      => null,
        ]);
    }

    private function notifyStudentStatusChanged(ShopOrder $order): void
    {
        if (!$order->student || !$order->student->user) {
            return;
        }

        $order->student->user->notify(new ShopOrderStatusChangedNotification($order));
    }

    private function getAdminReviewers()
    {
        return User::role('admin')->get();
    }
}
