<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id',
        'teacher_id',
        'lesson_date',
        'start_time',
        'end_time',
        'status',
        'topic',
        'description',
        'cancellation_reason',
    ];

    /**
     * Ma'lumot turlarini avtomatik konvertatsiya qilish.
     */
    protected $casts = [
        'lesson_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // --- Relationships (Aloqalar) ---

    /**
     * Dars tegishli bo'lgan guruh.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Darsni o'tuvchi o'qituvchi.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Shu darsdagi barcha davomatlar.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // --- Scopes (Filtrlash uchun qulayliklar) ---

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Bugungi darslarni olish uchun.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('lesson_date', today());
    }
    public function changes(): HasMany
    {
        return $this->hasMany(LessonChange::class)->orderBy('created_at', 'desc');
    }
}
