<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lesson_id',
        'student_id',
        'marked_by',
        'status',
        'late_minutes',
        'homework_score',
        'activity_score',
        'notes',
        'marked_at',
    ];

    /**
     * Ma'lumot turlarini formatlash.
     */
    protected $casts = [
        'status' => 'string', // Enum bo'lsa ham string sifatida olinadi
        'late_minutes' => 'integer',
        'homework_score' => 'integer',
        'activity_score' => 'integer',
        'marked_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // --- Aloqalar (Relationships) ---

    /**
     * Davomat tegishli bo'lgan dars.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Davomat qilingan talaba.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class); // Student modeli bor deb hisoblaymiz
    }

    /**
     * Davomatni belgilagan foydalanuvchi (O'qituvchi yoki Admin).
     */
    public function marker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    // --- Scopes (Filtrlash uchun qulayliklar) ---

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    // --- Audit Logging ---

    protected static function booted()
    {
        static::created(function ($attendance) {
            \Illuminate\Support\Facades\Log::info('Attendance created', [
                'attendance_id' => $attendance->id,
                'lesson_id' => $attendance->lesson_id,
                'student_id' => $attendance->student_id,
                'status' => $attendance->status,
                'marked_by' => $attendance->marked_by,
                'marked_at' => $attendance->marked_at,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        static::updated(function ($attendance) {
            $changes = $attendance->getChanges();
            if (!empty($changes)) {
                \Illuminate\Support\Facades\Log::info('Attendance updated', [
                    'attendance_id' => $attendance->id,
                    'lesson_id' => $attendance->lesson_id,
                    'student_id' => $attendance->student_id,
                    'changes' => $changes,
                    'old_values' => $attendance->getOriginal(),
                    'marked_by' => $attendance->marked_by,
                    'user_id' => auth()->id(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });

        static::deleted(function ($attendance) {
            \Illuminate\Support\Facades\Log::info('Attendance deleted', [
                'attendance_id' => $attendance->id,
                'lesson_id' => $attendance->lesson_id,
                'student_id' => $attendance->student_id,
                'status' => $attendance->status,
                'marked_by' => $attendance->marked_by,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}
