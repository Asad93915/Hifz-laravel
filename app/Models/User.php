<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
        'allow_to_create_classes',
        'allow_to_create_teachers',
        'allow_to_create_students',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define the relationship with the classes (One teacher can have many classes).
     * The teacher_id will link the user to a class.
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }

    /**
     * Check if the user has permission to create classes.
     * This can be useful for checking permissions in controllers.
     */
    public function canCreateClasses()
    {
        return $this->allow_to_create_classes;
    }

    /**
     * Check if the user has permission to create teachers.
     * This can be useful for checking permissions in controllers.
     */
    public function canCreateTeachers()
    {
        return $this->allow_to_create_teachers;
    }

    /**
     * Check if the user has permission to create students.
     * This can be useful for checking permissions in controllers.
     */
    public function canCreateStudents()
    {
        return $this->allow_to_create_students;
    }

    /**
     * Automatically hash the password before saving it to the database.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        // Automatically hash the password before saving to the database
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the role of the user.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return ucfirst($this->attributes['role']);  // Capitalize the role
    }

    /**
     * Define the relationship with Branch Model (optional)
     * Assuming you have a Branch model to link the branch_id
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Accessor for the user's permissions.
     *
     * @return array
     */
    public function getPermissionsAttribute()
    {
        return [
            'can_create_classes' => $this->canCreateClasses(),
            'can_create_teachers' => $this->canCreateTeachers(),
            'can_create_students' => $this->canCreateStudents(),
        ];
    }
}
