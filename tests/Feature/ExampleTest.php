<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    public function test_auth(): void {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v1.0/users');

        $response->assertStatus(401);
    }
}