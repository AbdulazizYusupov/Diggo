<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Director extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'commission_percentage', 'balance'];
    protected $casts = ['commission_percentage' => 'decimal:2', 'balance' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'director_school')->withTimestamps();
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function commissions()
    {
        return $this->hasMany(DirectorCommission::class);
    }
}
