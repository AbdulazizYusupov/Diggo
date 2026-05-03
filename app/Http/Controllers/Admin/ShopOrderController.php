<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use App\Services\Shop\ShopOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
{
    private ShopOrderService $shopOrderService;

    public function __construct(ShopOrderService $shopOrderService)
    {
        $this->shopOrderService = $shopOrderService;
    }
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
            'pending'   => ShopOrder::where('status', ShopOrder::STATUS_PENDING)->count(),
            'approved'  => ShopOrder::where('status', ShopOrder::STATUS_APPROVED)->count(),
            'delivered' => ShopOrder::where('status', ShopOrder::STATUS_DELIVERED)->count(),
            'cancelled' => ShopOrder::whereIn('status', [ShopOrder::STATUS_CANCELLED, ShopOrder::STATUS_REJECTED])->count(),
            'total_coins' => ShopOrder::whereIn('status', [ShopOrder::STATUS_APPROVED, ShopOrder::STATUS_DELIVERED])->sum('total_coins'),
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
        $request->validate(['admin_note' => 'nullable|string|max:500']);

        try {
            $this->shopOrderService->approveOrder(
                $order,
                Auth::user(),
                $request->get('admin_note'),
            );
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', "#{$order->order_number} buyurtmasi tasdiqlandi!");
    }

    public function deliver(Request $request, ShopOrder $order)
    {
        $request->validate([
            'delivery_info' => 'nullable|string|max:500',
            'admin_note'    => 'nullable|string|max:500',
        ]);

        try {
            $this->shopOrderService->deliverOrder(
                $order,
                Auth::user(),
                $request->get('admin_note'),
                $request->get('delivery_info'),
            );
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', "#{$order->order_number} yetkazildi deb belgilandi!");
    }

    public function reject(Request $request, ShopOrder $order)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        try {
            $this->shopOrderService->rejectOrder(
                $order,
                Auth::user(),
                $request->get('admin_note'),
            );
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', "#{$order->order_number} rad etildi va coinlar qaytarildi!");
    }
}
