<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cours extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'title',
        'code',
        'description',
        'price',
        'duration_months',
        'level',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_months' => 'integer',
        'is_active' => 'boolean'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
