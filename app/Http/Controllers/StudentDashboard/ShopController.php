<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use App\Services\Shop\ShopOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    private ShopOrderService $shopOrderService;

    public function __construct(ShopOrderService $shopOrderService)
    {
        $this->shopOrderService = $shopOrderService;
    }
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        $query = ShopProduct::with('category')
            ->active()
            ->inStock()
            ->orderBy('sort_order')
            ->orderByDesc('is_featured');

        // Kategoriya filtri
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Narx filtri
        if ($request->filled('price_from')) {
            $query->where('coin_price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('coin_price', '<=', $request->price_to);
        }

        // Qidiruv
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('brand', 'like', $search)
                  ->orWhere('short_description', 'like', $search);
            });
        }

        // Coinlarim yetadi toggle
        if ($request->boolean('affordable') && $student) {
            $query->where('coin_price', '<=', $student->coins ?? 0);
        }

        $perPage   = in_array($request->per_page, [8, 16, 24]) ? $request->per_page : 8;
        $products  = $query->paginate($perPage)->withQueryString();
        $categories = ShopCategory::active()->ordered()->get();

        return view('Panel.Student.Shop.index', compact('products', 'categories'));
    }

    /**
     * Mahsulot detail sahifasi
     */
    public function show(ShopProduct $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');

        $relatedProducts = ShopProduct::active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where('shop_category_id', $product->shop_category_id)
            ->take(5)
            ->get();

        return view('Panel.Student.Shop.show', compact('product', 'relatedProducts'));
    }

    /**
     * Buyurtma berish
     */
    public function order(Request $request, ShopProduct $product)
    {
        $request->validate([
            'quantity'     => 'required|integer|min:1',
            'student_note' => 'nullable|string|max:500',
        ]);

        $student = Auth::user()->student;

        try {
            $this->shopOrderService->placeOrder(
                product: $product,
                student: $student,
                quantity: $request->input('quantity'),
                studentNote: $request->input('student_note'),
            );
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('student.shop.orders')
            ->with('success', '🎉 Buyurtmangiz qabul qilindi! Tez orada tasdiqlanadi.');
    }

    /**
     * Buyurtmalarim ro'yxati
     */
    public function orders(Request $request)
    {
        $student = Auth::user()->student;

        $query = ShopOrder::with(['product.category'])
            ->where('student_id', $student->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('Panel.Student.Shop.orders', compact('orders'));
    }

    /**
     * Buyurtmani bekor qilish
     */
    public function cancel(Request $request, ShopOrder $order)
    {
        $student = Auth::user()->student;

        try {
            $this->shopOrderService->cancelOrder($order, $student);
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Buyurtma bekor qilindi va coinlaringiz qaytarildi.');
    }
}
