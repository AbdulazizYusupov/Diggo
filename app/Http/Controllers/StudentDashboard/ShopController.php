<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
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

        // Validatsiya
        if (!$product->is_active || !$product->is_in_stock) {
            return back()->with('error', 'Mahsulot mavjud emas.');
        }

        if (!$product->canStudentBuy($student->id)) {
            return back()->with('error', 'Siz bu mahsulotdan maksimal miqdorda xarid qilgansiz.');
        }

        $quantity   = $request->quantity;
        $totalCoins = $product->coin_price * $quantity;

        // Coin tekshirish
        if ($student->coins < $totalCoins) {
            return back()->with('error', 'Coiningiz yetarli emas.');
        }

        // Zaxira tekshirish
        if (!$product->is_unlimited && $product->stock_quantity < $quantity) {
            return back()->with('error', 'Zaxirada faqat ' . $product->stock_quantity . ' ta qolgan.');
        }

        DB::transaction(function () use ($product, $student, $request, $quantity, $totalCoins) {
            // Coin yechish
            $student->decrement('coins', $totalCoins);

            // Zaxirani kamaytirish
            if (!$product->is_unlimited) {
                $product->decrement('stock_quantity', $quantity);
            }

            // Buyurtma yaratish
            ShopOrder::create([
                'student_id'     => $student->id,
                'shop_product_id'=> $product->id,
                'quantity'       => $quantity,
                'coin_price'     => $product->coin_price,
                'total_coins'    => $totalCoins,
                'student_note'   => $request->student_note,
                'status'         => 'pending',
            ]);

            // total_sold oshirish
            $product->increment('total_sold', $quantity);
        });

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

        if ($order->student_id !== $student->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Faqat kutilmoqda holatidagi buyurtmani bekor qilish mumkin.');
        }

        DB::transaction(function () use ($order, $student) {
            // Coinni qaytarish
            $student->increment('coins', $order->total_coins);

            // Zaxirani qaytarish
            if ($order->product && !$order->product->is_unlimited) {
                $order->product->increment('stock_quantity', $order->quantity);
                $order->product->decrement('total_sold', $order->quantity);
            }

            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Buyurtma bekor qilindi va coinlaringiz qaytarildi.');
    }
}
