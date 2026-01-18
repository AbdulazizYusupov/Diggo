<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'salary_type',
        'salary_amount',
        'percent_per_student',
        'percent_per_payment',
        'is_active',
    ];

    protected $casts = [
        'salary_amount' => 'decimal:2',
        'percent_per_student' => 'decimal:2',
        'percent_per_payment' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Xodimning foydalanuvchi ma'lumotlari (ism, login, tel va h.k).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Faqat faol ishlayotgan xodimlarni olish.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Xodim oylik turini tekshirish uchun yordamchi metodlar.
     */
    public function isFixedSalary(): bool
    {
        return $this->salary_type === 'fixed';
    }

    public function isPercentSalary(): bool
    {
        return $this->salary_type === 'percent';
    }

    public function isPerStudentSalary(): bool
    {
        return $this->salary_type === 'per_student';
    }
}
