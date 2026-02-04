<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityResult extends Model
{
    protected $fillable = [
        'activity_id',
        'student_id',
        'earned_coins',
        'rank',
        'note'
    ];

    /**
     * Natija qaysi tadbirga tegishli ekanligi
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Natija qaysi talabaga tegishli ekanligi
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
