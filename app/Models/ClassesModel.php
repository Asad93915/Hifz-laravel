<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClassesModel extends Model
{
    use HasFactory;

    // Specify the table name explicitly
    protected $table = 'classes';

    // Allow mass assignment for the necessary fields
    protected $fillable = [
        'class_name',
        'class_short_description',
        'total_strength',
        'created_by',
        'date',
        'status',
        'active_days',
        'teacher_id',
    ];

    // Specify the columns that should be cast to specific data types
    protected $casts = [
        'active_days' => 'array',
        'date' => 'datetime:Y-m-d', // Ensures proper date format handling
    ];

    /**
     * Define the relationship to the teacher (User model).
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Define the relationship to the creator (User model).
     * This is optional, but useful if you want to track who created the class.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
