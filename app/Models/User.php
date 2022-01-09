<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'owner_name',
        'username',
        'email',
        'phone_number',
        'has_pro_account',
        'had_pro_account',
        'business_name',
        'number_of_employees',
        'business_description',
        'country',
        'state',
        'city',
        'general_layman_location',
        'password',
        'image',
        'can_create_store',
        'is_blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function proAccount()
    {
        return $this->hasOne(Employee::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
