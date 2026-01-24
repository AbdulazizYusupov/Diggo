<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonChange extends Model
{
    protected $fillable = [
        'lesson_id',
        'old_date',
        'new_date',
        'old_start_time',
        'new_start_time',
        'change_reason',
        'changed_by',
    ];

    protected $casts = [
        'old_date' => 'date',
        'new_date' => 'date',
    ];

    /**
     * O'zgarish qaysi darsga tegishli.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * O'zgarishni kim amalga oshirdi.
     */
    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
