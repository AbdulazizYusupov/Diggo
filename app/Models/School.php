<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'address', 'city', 'region', 'phone', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function directors()
    {
        return $this->belongsToMany(Director::class, 'director_school')->withTimestamps();
    }

    public function courses()
    {
        return $this->hasMany(Cours::class);
    }
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
