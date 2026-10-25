<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * One order per status the state machine actually has
 * (Admin\OrderService::$transitions), each with real OrderItems and a
 * matching Payment row — so admin order-management and payment endpoints
 * have something to show for every branch, not just "pending".
 */
class OrderSeeder extends Seeder
{
    protected const STATUSES_WITH_PAYMENT_STATUS = [
        'pending' => 'pending',
        'processing' => 'pending',
        'paid' => 'succeeded',
        'shipped' => 'succeeded',
        'delivered' => 'succeeded',
        'cancelled' => 'failed',
        'refunded' => 'refunded',
    ];

    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->inRandomOrder()->limit(20)->get();

        if (! $customer || $products->isEmpty()) {
            $this->command?->warn('Run UserSeeder, ProductSeeder, and AddressSeeder before OrderSeeder.');

            return;
        }

        $address = Address::where('user_id', $customer->id)->first()
            ?? Address::factory()->create(['user_id' => $customer->id]);

        foreach (self::STATUSES_WITH_PAYMENT_STATUS as $orderStatus => $paymentStatus) {
            $this->createOrder($customer, $address, $orderStatus, $paymentStatus, $products);
        }

        // A batch of extra random orders across random customers/statuses,
        // for pagination/filtering/stats testing at realistic volume.
        $otherCustomers = User::where('role', 'customer')->where('id', '!=', $customer->id)->inRandomOrder()->limit(10)->get();

        foreach ($otherCustomers as $otherCustomer) {
            $otherAddress = Address::where('user_id', $otherCustomer->id)->first();

            if (! $otherAddress) {
                continue;
            }

            $status = fake()->randomElement(array_keys(self::STATUSES_WITH_PAYMENT_STATUS));
            $this->createOrder($otherCustomer, $otherAddress, $status, self::STATUSES_WITH_PAYMENT_STATUS[$status], $products);
        }
    }

    protected function createOrder(User $user, Address $address, string $status, string $paymentStatus, $products): void
    {
        $lineItems = $products->random(min(3, $products->count()));
        $subtotal = 0;

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
            'status' => $status,
            'subtotal' => 0, // filled in below once line items are known
            'tax' => 0,
            'shipping_fee' => $status === 'pending' ? 0 : 5,
            'total' => 0,
            'currency' => 'USD',
            'shipping_address_id' => $address->id,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        foreach ($lineItems as $product) {
            $quantity = fake()->numberBetween(1, 3);
            $lineSubtotal = $product->price * $quantity;
            $subtotal += $lineSubtotal;

            $order->items()->create([
                'product_id' => $product->id,
                'product_name_snapshot' => $product->name,
                'price_snapshot' => $product->price,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
            ]);
        }

        $order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $order->shipping_fee,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'provider' => 'stripe',
            'checkout_session_id' => 'cs_test_seed_'.Str::random(16),
            'payment_intent_id' => $paymentStatus !== 'pending' ? 'pi_seed_'.Str::random(16) : null,
            'idempotency_key' => (string) Str::uuid(),
            'amount' => $order->total,
            'currency' => 'USD',
            'status' => $paymentStatus,
            'paid_at' => in_array($paymentStatus, ['succeeded', 'refunded']) ? now()->subDays(fake()->numberBetween(0, 10)) : null,
        ]);
    }
}