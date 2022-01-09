<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddStudent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'course',
        'email',
        'phone',
        'registration_date'
    ];
}
