<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Order;
use App\Models\ItemOrder;
use App\Models\Cart;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'discount',
        'image',
        'image_public_id',
        'category_id',
    ];

    // (1) Each product belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // (2) Product appears in many cart items
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // (3) Product has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // (4) Product has many item_orders (order line items)
    public function itemOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    // (5) Product belongs to many orders (through item_orders)
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'item_orders')
                    ->withPivot(['quantity', 'price'])   // or unit_price, subtotal if those exist
                    ->withTimestamps();
    }
}