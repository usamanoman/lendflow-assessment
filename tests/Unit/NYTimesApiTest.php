<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class NYTimesApiTest extends TestCase
{
    use RefreshDatabase; // Resets the database after each test
    /**
     * A basic unit test example.
     */
    

    public function test_authenticated_nybooks_success()
    {
         $user = User::factory()->create();
         $token = $user->createToken('API Token')->plainTextToken;
 
         $response = $this->withHeaders([
             'Authorization' => "Bearer $token"
         ])->getJson('/api/v1/nytimes/best-sellers-history');
 
         $response->assertStatus(200) // Expect HTTP 201 Created
                 ->assertJsonStructure([
                     'results', 'status'
                 ]);
    }


    public function test_unauthenticated_nybooks_failure()
    { 
         $response = $this->getJson('/api/v1/nytimes/best-sellers-history');
 
         $response->assertStatus(401);
    }


    public function test_authenticated_nybooks_failure()
    {
         // Temporarily set the NYTIMES_API_KEY environment variable to empty
         config(['services.nytimes.api_key' => '']);

         $user = User::factory()->create();
         $token = $user->createToken('API Token')->plainTextToken;
 
         $response = $this->withHeaders([
             'Authorization' => "Bearer $token"
         ])->getJson('/api/v1/nytimes/best-sellers-history');
 
         $response->assertStatus(500);
    }

}
