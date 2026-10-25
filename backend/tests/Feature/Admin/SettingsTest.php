<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_index_returns_defaults_when_nothing_is_stored(): void
    {
        $this->getJson('/admin/settings')
            ->assertOk()
            ->assertJsonPath('data.delivery_fee_standard', 5.0);
    }

    public function test_update_persists_and_is_reflected_immediately(): void
    {
        $this->patchJson('/admin/settings', ['delivery_fee_standard' => 12.5])
            ->assertOk()
            ->assertJsonPath('data.delivery_fee_standard', 12.5);

        $this->getJson('/admin/settings')->assertJsonPath('data.delivery_fee_standard', 12.5);
    }
}
