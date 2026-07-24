<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_health_check_returns_successful_response(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'online',
                     'version' => '1.0.0'
                 ]);
    }

    public function test_user_can_login_via_api_and_receive_token(): void
    {
        $user = User::factory()->create([
            'email' => 'testapi@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testapi@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user',
                         'token',
                         'token_type'
                     ]
                 ]);
    }

    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->getJson('/api/countries');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_countries(): void
    {
        $user = User::factory()->create();
        Country::create([
            'name' => 'Singapore',
            'code' => 'SGP',
            'region' => 'Southeast Asia'
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/countries');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }
}
