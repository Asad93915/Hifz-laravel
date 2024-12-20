<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // Define the fillable properties to allow mass assignment
    protected $fillable = [
        'branch_name', 
        'branch_contact_number', 
        'branch_address',
    ];
}
