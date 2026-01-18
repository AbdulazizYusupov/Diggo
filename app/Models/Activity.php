<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'group_id',
        'school_id',
        'description',
        'date',
        'max_coins'
    ];

    protected $casts = [
        'date' => 'date',
        'max_coins' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }
}
