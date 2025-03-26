<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserApiTest extends TestCase
{
    use RefreshDatabase; // Resets the database after each test

    public function test_can_create_user()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201) // Expect HTTP 201 Created
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'created_at']
                 ]);
    }

    public function test_can_login_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200) // Expect HTTP 201 Created
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email']
                 ]);
    }


    public function test_can_login_user_failure()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@john.com',
            'password' => 'test1234',
        ]);

        $response->assertStatus(422) // Expect HTTP 201 Created
                 ->assertJsonStructure([
                     'message', 'errors'
                 ]);
    }


    public function test_authenticated_user_can_access_protected_route_get_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/v1/user');

        $response->assertStatus(200);
    }
}
