<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'salary_type',
        'salary_amount',
        'percent_per_student',
        'is_active',
    ];

    /**
     * Ma'lumot turlarini avtomatik formatlash.
     */
    protected $casts = [
        'salary_amount' => 'decimal:2',
        'percent_per_student' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * O'qituvchining foydalanuvchi ma'lumotlari (ism, tel, email va h.k).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * O'qituvchi biriktirilgan guruhlar.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_teacher')
            ->using(GroupTeacher::class)
            ->withPivot(['is_primary', 'assigned_at'])
            ->withTimestamps();
    }

    /**
     * O'qituvchining davomat jurnali (qancha dars o'tganini hisoblash uchun).
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }

    /**
     * Scope: Faqat hozirda dars o'tayotgan o'qituvchilar.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Oylik hisoblash turini tekshirish uchun helperlar.
     */
    public function hasFixedSalary(): bool
    {
        return $this->salary_type === 'fixed';
    }
    public function hasHourlySalary(): bool
    {
        return $this->salary_type === 'hourly';
    }
    public function hasPerGroupSalary(): bool
    {
        return $this->salary_type === 'per_group';
    }
    public function hasPercentSalary(): bool
    {
        return $this->salary_type === 'percent';
    }
}
