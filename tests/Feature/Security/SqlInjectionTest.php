<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqlInjectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admission_search_is_protected_against_sql_injection(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        // Malicious payload that would return true in a raw SQL query
        $maliciousPayload = "123' OR '1'='1";
        
        $response = $this->actingAs($user)->get('/admission/search?q=' . urlencode($maliciousPayload));
        
        // The application should treat it as a literal string and find nothing
        $response->assertStatus(200)
                 ->assertJson([
                     'found' => false
                 ]);
    }

    public function test_login_is_protected_against_sql_injection(): void
    {
        $maliciousPayload = "admin' --";
        
        $response = $this->post('/login', [
            'username' => $maliciousPayload,
            'password' => 'password',
        ]);

        // Should just fail authentication
        $this->assertGuest();
        $response->assertSessionHasErrors('username');
    }
}
