<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class PaymentManagementTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_refund_marks_payment_and_order_refunded(): void
    {
        $order = Order::factory()->paid()->create();
        $payment = Payment::factory()->succeeded()->create(['order_id' => $order->id]);

        $this->postJson("/admin/payments/{$payment->id}/refund")
            ->assertOk()
            ->assertJsonPath('data.status', 'refunded');

        $this->assertSame('refunded', $order->fresh()->status);
    }

    public function test_list_can_be_filtered_by_status(): void
    {
        Payment::factory()->create(['status' => 'pending']);
        Payment::factory()->succeeded()->create();

        $response = $this->getJson('/admin/payments?status=succeeded');

        collect($response->json('data.items'))->each(
            fn ($p) => $this->assertSame('succeeded', $p['status'])
        );
    }
}
