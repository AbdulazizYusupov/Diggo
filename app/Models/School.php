<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'address', 'city', 'region', 'phone', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    
    public function director(): HasOne
    {
        return $this->hasOne(Director::class, 'school_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
