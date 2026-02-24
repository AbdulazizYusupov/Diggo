<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'student_id',
        'shop_product_id',
        'quantity',
        'coin_price',
        'total_coins',
        'status',
        'student_note',
        'admin_note',
        'delivery_info',
        'processed_by',
        'processed_at',
        'delivered_at',
    ];

    protected $casts = [
        'quantity'      => 'integer',
        'coin_price'    => 'integer',
        'total_coins'   => 'integer',
        'processed_at'  => 'datetime',
        'delivered_at'  => 'datetime',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $year   = now()->format('Y');
        $last   = static::whereYear('created_at', $year)->max('id') ?? 0;
        return 'ORD-' . $year . '-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'shop_product_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeApproved($query)  { return $query->where('status', 'approved'); }
    public function scopeDelivered($query) { return $query->where('status', 'delivered'); }
    public function scopeCancelled($query) { return $query->where('status', 'cancelled'); }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending'   => ['label' => 'Kutilmoqda',    'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',    'icon' => 'clock'],
            'approved'  => ['label' => 'Tasdiqlangan',  'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',        'icon' => 'check-circle'],
            'delivered' => ['label' => 'Yetkazilgan',   'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400','icon' => 'truck'],
            'cancelled' => ['label' => 'Bekor qilindi', 'class' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',           'icon' => 'x-circle'],
            'rejected'  => ['label' => 'Rad etildi',    'class' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',        'icon' => 'ban'],
            default     => ['label' => $this->status,   'class' => 'bg-gray-100 text-gray-600',                                               'icon' => 'question'],
        };
    }
}
