<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ShopProduct::with('category')->withCount('orders');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('shop_category_id', $request->get('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        if ($request->filled('stock')) {
            match($request->get('stock')) {
                'in'      => $query->where('stock_quantity', '>', 5)->where('is_unlimited', false),
                'low'     => $query->whereBetween('stock_quantity', [1, 5])->where('is_unlimited', false),
                'out'     => $query->where('stock_quantity', 0)->where('is_unlimited', false),
                'unlimited'=> $query->where('is_unlimited', true),
                default   => null,
            };
        }

        $sortBy  = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $products   = $query->paginate(15)->withQueryString();
        $categories = ShopCategory::active()->ordered()->get();

        $stats = [
            'total'     => ShopProduct::count(),
            'active'    => ShopProduct::where('is_active', true)->count(),
            'out_stock' => ShopProduct::where('stock_quantity', 0)->where('is_unlimited', false)->count(),
            'featured'  => ShopProduct::where('is_featured', true)->count(),
        ];

        return view('Shop.Product.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = ShopCategory::active()->ordered()->get();
        return view('Shop.Product.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_category_id'  => 'required|exists:shop_categories,id',
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:shop_products,slug',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'coin_price'        => 'required|integer|min:1',
            'stock_quantity'    => 'required_unless:is_unlimited,1|integer|min:0',
            'is_unlimited'      => 'boolean',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sku'               => 'nullable|string|max:100|unique:shop_products,sku',
            'brand'             => 'nullable|string|max:100',
            'max_per_student'   => 'integer|min:1|max:99',
            'sort_order'        => 'integer|min:0',
            'attributes'        => 'nullable|string', // JSON string from form
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_unlimited'] = $request->boolean('is_unlimited');
        $validated['is_active']    = $request->boolean('is_active', true);
        $validated['is_featured']  = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('shop/products', 'public');
        }

        if ($request->hasFile('images')) {
            $extra = [];
            foreach ($request->file('images') as $img) {
                $extra[] = $img->store('shop/products', 'public');
            }
            $validated['images'] = $extra;
        }

        // Parse attributes JSON
        if (!empty($validated['attributes'])) {
            $validated['attributes'] = json_decode($validated['attributes'], true) ?? null;
        }

        ShopProduct::create($validated);

        return redirect()->route('admin.shop.products.index')
            ->with('success', "'{$validated['name']}' mahsuloti muvaffaqiyatli qo'shildi!");
    }

    public function show(ShopProduct $product)
    {
        $product->load('category');
        $orders = $product->orders()->with('student.user')->latest()->paginate(10);

        $orderStats = [
            'total'     => $product->orders()->count(),
            'pending'   => $product->orders()->where('status', 'pending')->count(),
            'delivered' => $product->orders()->where('status', 'delivered')->count(),
            'total_coins' => $product->orders()->whereIn('status', ['approved','delivered'])->sum('total_coins'),
        ];

        return view('Shop.Product.show', compact('product', 'orders', 'orderStats'));
    }

    public function edit(ShopProduct $product)
    {
        $categories = ShopCategory::active()->ordered()->get();
        return view('Shop.Product.form', compact('product', 'categories'));
    }

    public function update(Request $request, ShopProduct $product)
    {
        $validated = $request->validate([
            'shop_category_id'  => 'required|exists:shop_categories,id',
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:shop_products,slug,' . $product->id,
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'coin_price'        => 'required|integer|min:1',
            'stock_quantity'    => 'required_unless:is_unlimited,1|integer|min:0',
            'is_unlimited'      => 'boolean',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sku'               => 'nullable|string|max:100|unique:shop_products,sku,' . $product->id,
            'brand'             => 'nullable|string|max:100',
            'max_per_student'   => 'integer|min:1|max:99',
            'sort_order'        => 'integer|min:0',
        ]);

        $validated['is_unlimited'] = $request->boolean('is_unlimited');
        $validated['is_active']    = $request->boolean('is_active', true);
        $validated['is_featured']  = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $validated['image'] = $request->file('image')->store('shop/products', 'public');
        }

        if ($request->hasFile('images')) {
            if ($product->images) {
                foreach ($product->images as $old) Storage::disk('public')->delete($old);
            }
            $extra = [];
            foreach ($request->file('images') as $img) {
                $extra[] = $img->store('shop/products', 'public');
            }
            $validated['images'] = $extra;
        }

        $product->update($validated);

        return redirect()->route('admin.shop.products.index')
            ->with('success', "'{$product->name}' mahsuloti yangilandi!");
    }

    public function destroy(ShopProduct $product)
    {
        if ($product->orders()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', "Faol buyurtmalari mavjud. Avval buyurtmalarni yoping.");
        }

        if ($product->image) Storage::disk('public')->delete($product->image);
        if ($product->images) {
            foreach ($product->images as $img) Storage::disk('public')->delete($img);
        }

        $product->delete();

        return redirect()->route('admin.shop.products.index')
            ->with('success', "'{$product->name}' o'chirildi!");
    }

    public function toggleStatus(ShopProduct $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', "'{$product->name}' holati o'zgartirildi!");
    }

    public function toggleFeatured(ShopProduct $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return back()->with('success', "'{$product->name}' featured holati o'zgartirildi!");
    }
}
