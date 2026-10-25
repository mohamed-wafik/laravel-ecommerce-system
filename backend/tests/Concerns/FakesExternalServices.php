<?php

namespace Tests\Concerns;

use App\Services\CloudinaryService;
use App\Services\Payment\PaymentGatewayFactory;
use Tests\Fakes\FakeCloudinaryService;
use Tests\Fakes\FakePaymentGateway;

/**
 * Binds fake implementations of every external service (Cloudinary,
 * Stripe) into the container. Because the app depends on
 * CloudinaryService/PaymentGateway rather than instantiating SDK clients
 * inline wherever they're needed, swapping in a fake for tests is a
 * one-line container bind — no HTTP mocking library, no real API keys,
 * no network access required to run the suite. Direct product of the
 * Dependency Inversion work done in the app itself.
 */
trait FakesExternalServices
{
    protected FakeCloudinaryService $fakeCloudinary;

    protected FakePaymentGateway $fakePaymentGateway;

    protected function fakeExternalServices(): void
    {
        $this->fakeCloudinary = new FakeCloudinaryService();
        $this->app->instance(CloudinaryService::class, $this->fakeCloudinary);

        $this->fakePaymentGateway = new FakePaymentGateway();

        $factory = $this->createMock(PaymentGatewayFactory::class);
        $factory->method('make')->willReturn($this->fakePaymentGateway);
        $this->app->instance(PaymentGatewayFactory::class, $factory);
    }
}
