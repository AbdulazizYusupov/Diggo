<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ShopOrder::with(['student.user', 'product.category', 'processedBy'])
            ->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas(
                        'student.user',
                        fn($u) =>
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                    );
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('product_id')) {
            $query->where('shop_product_id', $request->get('product_id'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->get('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->get('to_date'));
        }

        $orders   = $query->paginate(20)->withQueryString();
        $products = ShopProduct::orderBy('name')->get();

        $stats = [
            'total'     => ShopOrder::count(),
            'pending'   => ShopOrder::where('status', 'pending')->count(),
            'approved'  => ShopOrder::where('status', 'approved')->count(),
            'delivered' => ShopOrder::where('status', 'delivered')->count(),
            'cancelled' => ShopOrder::whereIn('status', ['cancelled', 'rejected'])->count(),
            'total_coins' => ShopOrder::whereIn('status', ['approved', 'delivered'])->sum('total_coins'),
        ];

        return view('Shop.Order.index', compact('orders', 'products', 'stats'));
    }

    public function show(ShopOrder $order)
    {
        $order->load(['student.user', 'product.category', 'processedBy']);
        return view('Shop.Order.show', compact('order'));
    }

    public function approve(Request $request, ShopOrder $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Faqat kutilayotgan buyurtmalarni tasdiqlash mumkin.');
        }

        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $order->update([
            'status'       => 'approved',
            'admin_note'   => $request->get('admin_note'),
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', "#{$order->order_number} buyurtmasi tasdiqlandi!");
    }

    public function deliver(Request $request, ShopOrder $order)
    {
        if ($order->status !== 'approved') {
            return back()->with('error', 'Faqat tasdiqlangan buyurtmalarni yetkazildi deb belgilash mumkin.');
        }

        $request->validate([
            'delivery_info' => 'nullable|string|max:500',
            'admin_note'    => 'nullable|string|max:500',
        ]);

        $order->update([
            'status'        => 'delivered',
            'delivery_info' => $request->get('delivery_info'),
            'admin_note'    => $request->get('admin_note'),
            'processed_by'  => Auth::id(),
            'delivered_at'  => now(),
        ]);

        return back()->with('success', "#{$order->order_number} yetkazildi deb belgilandi!");
    }

    public function reject(Request $request, ShopOrder $order)
    {
        if (!in_array($order->status, ['pending', 'approved'])) {
            return back()->with('error', 'Bu buyurtmani rad etib bo\'lmaydi.');
        }

        $request->validate(['admin_note' => 'required|string|max:500']);

        DB::transaction(function () use ($order, $request) {
            // Coinlarni qaytarish
            $this->refundCoins(
                order: $order,
                description: "Do'kon buyurtmasi rad etildi: #{$order->order_number}",
                actorUserId: Auth::id(),
            );

            // Zaxirani qaytarish
            if (!$order->product->is_unlimited) {
                $order->product->increment('stock_quantity', $order->quantity);
            }

            $order->update([
                'status'       => 'rejected',
                'admin_note'   => $request->get('admin_note'),
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);
        });

        return back()->with('success', "#{$order->order_number} rad etildi va coinlar qaytarildi!");
    }

    public function cancel(Request $request, ShopOrder $order)
    {
        if ($order->student_id !== auth()->user()->student?->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Faqat kutilayotgan buyurtmalarni bekor qilish mumkin.');
        }

        DB::transaction(function () use ($order) {
            // Coinlarni qaytarish
            $this->refundCoins(
                order: $order,
                description: "Do'kon buyurtmasi bekor qilindi: #{$order->order_number}",
                actorUserId: Auth::id(),
            );

            if (!$order->product->is_unlimited) {
                $order->product->increment('stock_quantity', $order->quantity);
            }

            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', "Buyurtma bekor qilindi va coinlar qaytarildi!");
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Studentga coinlarni qaytaradi va CoinTransaction yozadi.
     */
    private function refundCoins(ShopOrder $order, string $description, ?int $actorUserId): void
    {
        // Studentning joriy coin balansini hisoblaymiz
        $currentBalance = CoinTransaction::where('student_id', $order->student_id)
            ->sum('coins');

        $balanceAfter = $currentBalance + $order->total_coins;

        CoinTransaction::create([
            'student_id'       => $order->student_id,
            'user_id'          => $actorUserId,   // Kim qaytardi (admin yoki o'zi)
            'activity_id'      => null,            // Do'kon refundi — activity bog'liq emas
            'coins'            => $order->total_coins, // Musbat: qaytarilmoqda
            'type'             => 'refund',
            'description'      => $description,
            'balance_after'    => $balanceAfter,
            'transaction_date' => now()->toDateString(),
            'expiry_date'      => null,            // Qaytarilgan coinlar muddatsiz
        ]);
    }
}
