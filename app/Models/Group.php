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
        'cours_id',
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

    public function course()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'group_teachers',
            'group_id',
            'teacher_id'
        )->withPivot('is_primary', 'assigned_at')
            ->withTimestamps();;
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
    // Group.php modeli
    public function groupStudents()
    {
        // Bu GroupStudent degan alohida Model borligini anglatadi
        return $this->hasMany(GroupStudent::class, 'group_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function teacher()
    {
        // Agar o'qituvchi bevosita guruhga bog'langan bo'lsa
        return $this->belongsTo(Teacher::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhereHas('course', fn($c) => $c->where('name', 'like', "%{$term}%"));
        });
    }
    // app/Models/Group.php ichiga

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
