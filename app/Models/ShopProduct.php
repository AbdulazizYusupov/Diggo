<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ShopProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'coin_price',
        'stock_quantity',
        'is_unlimited',
        'is_active',
        'is_featured',
        'image',
        'images',
        'sku',
        'brand',
        'attributes',
        'max_per_student',
        'sort_order',
        'total_sold',
    ];

    protected $casts = [
        'coin_price'      => 'integer',
        'stock_quantity'  => 'integer',
        'is_unlimited'    => 'boolean',
        'is_active'       => 'boolean',
        'is_featured'     => 'boolean',
        'images'          => 'array',
        'attributes'      => 'array',
        'max_per_student' => 'integer',
        'sort_order'      => 'integer',
        'total_sold'      => 'integer',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
            if (empty($model->sku)) {
                $model->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'shop_category_id');
    }

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'shop_product_id');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('is_unlimited', true)
              ->orWhere('stock_quantity', '>', 0);
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('shop_category_id', $categoryId);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-product.png');
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->is_unlimited || $this->stock_quantity > 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->is_unlimited) return 'unlimited';
        if ($this->stock_quantity === 0) return 'out';
        if ($this->stock_quantity <= 5) return 'low';
        return 'in';
    }

    public function getStockBadgeAttribute(): array
    {
        return match($this->stock_status) {
            'unlimited' => ['label' => 'Cheksiz',        'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
            'out'       => ['label' => 'Tugagan',         'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
            'low'       => ['label' => 'Kam qoldi',       'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
            default     => ['label' => 'Mavjud',          'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'],
        };
    }

    /**
     * Student bu mahsulotni yana xarid qila oladimi tekshirish
     */
    public function canStudentBuy(int $studentId): bool
    {
        if (!$this->is_active || !$this->is_in_stock) return false;

        $bought = $this->orders()
            ->where('student_id', $studentId)
            ->whereIn('status', ['pending', 'approved', 'delivered'])
            ->sum('quantity');

        return $bought < $this->max_per_student;
    }
}
