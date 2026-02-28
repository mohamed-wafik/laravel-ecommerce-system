<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemOrder extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Accessors for compatibility
    public function getUnitPriceAttribute()
    {
        return $this->price;
    }

    public function getSubtotalAttribute()
    {
        return $this->total;
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'item_orders')
                    ->withPivot(['quantity', 'price'])   // or unit_price, subtotal if those exist
                    ->withTimestamps();
    }
}