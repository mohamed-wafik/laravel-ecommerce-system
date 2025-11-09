<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\ItemOrder;

it('creates models via factories and links relationships', function () {
    // Category <-> Product
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->category)->not->toBeNull();
    expect($product->category->id)->toBe($category->id);

    // User -> Order
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'total_amount' => 0]);

    expect($order->user)->not->toBeNull();
    expect($order->user->id)->toBe($user->id);

    // ItemOrder linking Order and Product
    $quantity = 2;
    $unitPrice = $product->price;
    $subtotal = round($quantity * $unitPrice, 2);

    $item = ItemOrder::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'subtotal' => $subtotal,
    ]);

    expect($order->itemOrders()->count())->toBe(1);
    expect($item->product->id)->toBe($product->id);
});
