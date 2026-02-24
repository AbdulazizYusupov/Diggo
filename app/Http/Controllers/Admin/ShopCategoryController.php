<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ShopCategory::withCount('products');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $categories = $query->ordered()->paginate(15)->withQueryString();

        $totalCategories  = ShopCategory::count();
        $activeCategories = ShopCategory::where('is_active', true)->count();
        $totalProducts    = ShopProduct::count();

        return view('Shop.Category.index', compact(
            'categories', 'totalCategories', 'activeCategories', 'totalProducts'
        ));
    }

    public function create()
    {
        $colors = $this->getColors();
        return view('Shop.Category.form', compact('colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:shop_categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'required|in:indigo,emerald,sky,amber,rose,purple,orange,teal',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Slug uniqueness
        $base = $validated['slug'];
        $i = 1;
        while (ShopCategory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $base . '-' . $i++;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('shop/categories', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        ShopCategory::create($validated);

        return redirect()->route('admin.shop.categories.index')
            ->with('success', "'{$validated['name']}' kategoriyasi muvaffaqiyatli qo'shildi!");
    }

    public function show(ShopCategory $category)
    {
        $category->loadCount('products');
        $products = $category->products()->with('orders')->paginate(10);
        return view('Shop.Category.show', compact('category', 'products'));
    }

    public function edit(ShopCategory $category)
    {
        $colors = $this->getColors();
        return view('Shop.Category.form', compact('category', 'colors'));
    }

    public function update(Request $request, ShopCategory $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:shop_categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'required|in:indigo,emerald,sky,amber,rose,purple,orange,teal',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('shop/categories', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('admin.shop.categories.index')
            ->with('success', "'{$category->name}' kategoriyasi yangilandi!");
    }

    public function destroy(ShopCategory $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', "Bu kategoriyada mahsulotlar mavjud. Avval mahsulotlarni o'chiring yoki boshqa kategoriyaga o'tkazing.");
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.shop.categories.index')
            ->with('success', "'{$category->name}' kategoriyasi o'chirildi!");
    }

    public function toggleStatus(ShopCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        $status = $category->is_active ? 'faollashtirildi' : 'o\'chirildi';
        return back()->with('success', "'{$category->name}' {$status}!");
    }

    private function getColors(): array
    {
        return [
            'indigo'  => ['label' => 'Indigo',   'preview' => 'bg-indigo-500'],
            'emerald' => ['label' => 'Yashil',   'preview' => 'bg-emerald-500'],
            'sky'     => ['label' => 'Ko\'k',    'preview' => 'bg-sky-500'],
            'amber'   => ['label' => 'Sariq',    'preview' => 'bg-amber-500'],
            'rose'    => ['label' => 'Qizil',    'preview' => 'bg-rose-500'],
            'purple'  => ['label' => 'Binafsha', 'preview' => 'bg-purple-500'],
            'orange'  => ['label' => 'To\'q sariq','preview' => 'bg-orange-500'],
            'teal'    => ['label' => 'Moviy',    'preview' => 'bg-teal-500'],
        ];
    }
}
