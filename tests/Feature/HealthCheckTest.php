<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HealthCheckTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEntryPoint()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
