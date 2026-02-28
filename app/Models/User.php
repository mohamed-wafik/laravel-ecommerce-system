<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'avatar_public_id',
        'faceback_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================
    // 🔥 RELATIONS
    // ============================

    // 1) User has many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // 2) User has many cart items
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // 3) User has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // 4) (Optional) User has many ordered items through orders
    public function itemOrders()
    {
        return $this->hasManyThrough(ItemOrder::class, Order::class, 'user_id', 'order_id');
    }
}