<?php

namespace Tests\Feature;

use Database\Seeders\CustomGiftSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ensures the storefront homepage renders once merchandising tables exist.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(CustomGiftSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
