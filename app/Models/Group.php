<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'course_id',
        'start_time',
        'end_time',
        'days_of_week',
        'capacity',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function course()
    {
        return $this->belongsTo(Cours::class);
    }


    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'group_teachers',
            'group_id',
            'teacher_id'
        );
    }


    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'group_students',
            'group_id',
            'student_id'
        );
    }


    public function activeStudents()
    {
        return $this->belongsToMany(Student::class, 'group_students')
            ->wherePivot('status', 'active')
            ->withPivot('joined_at', 'left_at', 'status')->withTimestamps();
    }
}
