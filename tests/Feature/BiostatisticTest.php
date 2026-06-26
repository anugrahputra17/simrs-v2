<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BiostatisticTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_biostatistic_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'director']);
        
        $response = $this->actingAs($user)->get('/biostatistic');
        $response->assertStatus(200);
    }
}
