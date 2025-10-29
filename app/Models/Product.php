<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Order;
use App\Models\ItemOrder;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'discount',
        'image',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function itemOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'item_orders')
                    ->withPivot(['quantity', 'unit_price', 'subtotal'])
                    ->withTimestamps();
    }
}
