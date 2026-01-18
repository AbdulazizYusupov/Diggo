<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupStudent extends Model
{
    protected $fillable = [
        'group_id',
        'student_id',
        'joined_at',
        'left_at',
        'status',
        'transfer_reason',
        'transferred_to_group_id',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'left_at' => 'date',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function transferredToGroup(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'transferred_to_group_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
