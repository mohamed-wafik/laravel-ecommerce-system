<?php

namespace Tests\Feature\Webhooks;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\StripeWebhookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Exercises StripeWebhookService directly with hand-built event payloads
 * shaped like what the Stripe SDK hands the controller after signature
 * verification — that verification step is a thin call into a
 * well-tested third-party SDK, so the useful thing to test here is what
 * this app DOES with a verified event, not Stripe's own crypto.
 */
class StripeWebhookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function stripeEvent(string $id, string $type, object $object): object
    {
        // Anonymous class so ->toArray() works like the real Stripe\Event does.
        return new class($id, $type, $object)
        {
            public string $id;

            public string $type;

            public object $data;

            public function __construct(string $id, string $type, object $object)
            {
                $this->id = $id;
                $this->type = $type;
                $this->data = (object) ['object' => $object];
            }

            public function toArray(): array
            {
                return ['id' => $this->id, 'type' => $this->type];
            }
        };
    }

    public function test_checkout_session_completed_marks_payment_and_order_paid(): void
    {
        $cart = Cart::factory()->create(['status' => 'active']);
        $order = Order::factory()->create(['user_id' => $cart->user_id, 'status' => 'pending']);
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'stripe_checkout_session_id' => 'cs_test_123',
            'status' => 'pending',
        ]);

        $event = $this->stripeEvent('evt_1', 'checkout.session.completed', (object) [
            'id' => 'cs_test_123',
            'payment_intent' => 'pi_test_123',
            'payment_status' => 'paid',
        ]);

        app(StripeWebhookService::class)->handle($event);

        $this->assertSame('succeeded', $payment->fresh()->status);
        $this->assertSame('paid', $order->fresh()->status);
        $this->assertSame('converted', $cart->fresh()->status);
    }

    public function test_checkout_session_completed_with_unpaid_status_does_not_mark_paid(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'stripe_checkout_session_id' => 'cs_test_456',
            'status' => 'pending',
        ]);

        $event = $this->stripeEvent('evt_2', 'checkout.session.completed', (object) [
            'id' => 'cs_test_456',
            'payment_intent' => 'pi_test_456',
            'payment_status' => 'unpaid',
        ]);

        app(StripeWebhookService::class)->handle($event);

        $this->assertSame('pending', $payment->fresh()->status);
        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_checkout_session_expired_cancels_order_and_restocks(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $order = Order::factory()->create(['status' => 'pending']);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name_snapshot' => $product->name,
            'price_snapshot' => $product->price,
            'quantity' => 3,
            'subtotal' => $product->price * 3,
        ]);
        Payment::factory()->create(['order_id' => $order->id, 'stripe_checkout_session_id' => 'cs_test_789']);

        $event = $this->stripeEvent('evt_3', 'checkout.session.expired', (object) ['id' => 'cs_test_789']);

        app(StripeWebhookService::class)->handle($event);

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(8, $product->fresh()->stock_quantity);
    }

    public function test_duplicate_event_id_is_processed_only_once(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'stripe_checkout_session_id' => 'cs_test_dup',
            'status' => 'pending',
        ]);

        $event = $this->stripeEvent('evt_dup', 'checkout.session.completed', (object) [
            'id' => 'cs_test_dup',
            'payment_intent' => 'pi_test_dup',
            'payment_status' => 'paid',
        ]);

        $service = app(StripeWebhookService::class);
        $service->handle($event);
        $service->handle($event); // replay — must be a no-op the second time

        $this->assertDatabaseCount('webhook_events', 1);
    }

    public function test_charge_refunded_marks_payment_and_order_refunded_and_restocks(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 2]);
        $order = Order::factory()->paid()->create();
        $order->items()->create([
            'product_id' => $product->id,
            'product_name_snapshot' => $product->name,
            'price_snapshot' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);
        Payment::factory()->succeeded()->create([
            'order_id' => $order->id,
            'stripe_payment_intent_id' => 'pi_refund_test',
        ]);

        $event = $this->stripeEvent('evt_refund', 'charge.refunded', (object) ['payment_intent' => 'pi_refund_test']);

        app(StripeWebhookService::class)->handle($event);

        $this->assertSame('refunded', $order->fresh()->status);
        $this->assertSame(3, $product->fresh()->stock_quantity);
    }
}
