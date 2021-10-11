<?php

namespace Tests\Feature;

use App\Models\ActiveCall;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActiveCallTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function register(){
        ActiveCall::factory()->count(1)->create();
        $this->assertDatabaseCount('active_calls', 1);
    }
}
