<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\ItemOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_date',
        'country', 
        'payment_status',
        'payment_tsession_id',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itemOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'item_orders')
                    ->withPivot(['quantity', 'unit_price', 'subtotal'])
                    ->withTimestamps();
    }
}