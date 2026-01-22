<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'user_id',
        'activity_id',
        'coins',
        'type',
        'description',
        'balance_after',
        'transaction_date',
        'expiry_date'
    ];

    protected $casts = [
        'coins' => 'integer',
        'balance_after' => 'integer',
        'transaction_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function awardedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
