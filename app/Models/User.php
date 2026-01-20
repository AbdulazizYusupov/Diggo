<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $fillable = [
        'name',
        'surname',
        'middle_name',
        'phone',
        'password',
        'photo',
        'birth_date',
        'gender',
        'address',
        'city',
        'region',
        'is_active',
    ];
    // protected $casts = ['is_active' => 'boolean', 'birth_date' => 'date'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'birth_date' => 'date'
        ];
    }
    public function getFullNameAttribute(): string
    {
        return trim("{$this->surname} {$this->name} {$this->middle_name}");
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
    public function director()
    {
        return $this->hasOne(Director::class);
    }
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function teachingGroups()
    {
        return $this->belongsToMany(Group::class, 'group_teacher', 'teacher_id', 'group_id')
            ->withPivot('is_primary', 'assigned_at')->withTimestamps();
    }
}
