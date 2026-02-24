<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ShopCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'shop_category_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(ShopProduct::class, 'shop_category_id')
                    ->where('is_active', true);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // Tailwind color classes map
    public function getColorClassesAttribute(): array
    {
        $map = [
            'indigo' => ['bg' => 'bg-indigo-100 dark:bg-indigo-500/20', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-200 dark:border-indigo-500/30'],
            'emerald'=> ['bg' => 'bg-emerald-100 dark:bg-emerald-500/20','text' => 'text-emerald-600 dark:text-emerald-400','border'=> 'border-emerald-200 dark:border-emerald-500/30'],
            'sky'    => ['bg' => 'bg-sky-100 dark:bg-sky-500/20',        'text' => 'text-sky-600 dark:text-sky-400',        'border'=> 'border-sky-200 dark:border-sky-500/30'],
            'amber'  => ['bg' => 'bg-amber-100 dark:bg-amber-500/20',    'text' => 'text-amber-600 dark:text-amber-400',    'border'=> 'border-amber-200 dark:border-amber-500/30'],
            'rose'   => ['bg' => 'bg-rose-100 dark:bg-rose-500/20',      'text' => 'text-rose-600 dark:text-rose-400',      'border'=> 'border-rose-200 dark:border-rose-500/30'],
            'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-500/20',  'text' => 'text-purple-600 dark:text-purple-400',  'border'=> 'border-purple-200 dark:border-purple-500/30'],
            'orange' => ['bg' => 'bg-orange-100 dark:bg-orange-500/20',  'text' => 'text-orange-600 dark:text-orange-400',  'border'=> 'border-orange-200 dark:border-orange-500/30'],
            'teal'   => ['bg' => 'bg-teal-100 dark:bg-teal-500/20',      'text' => 'text-teal-600 dark:text-teal-400',      'border'=> 'border-teal-200 dark:border-teal-500/30'],
        ];

        return $map[$this->color] ?? $map['indigo'];
    }
}
