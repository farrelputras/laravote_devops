<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

class VotingSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_voting_session_generates_tokens()
    {
        Gate::define('manage-users', fn() => true);

        $users = User::factory()->count(2)->create(['is_eligible' => true]);

        $this->actingAs($users->first());

        $response = $this->post(route('voting.session'));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        foreach ($users as $user) {
            $this->assertNotNull($user->fresh()->token);
        }
    }

    public function test_end_voting_session_resets_user_data()
    {
        Gate::define('manage-users', fn() => true);

        $user = User::factory()->create([
            'roles' => '["VOTER"]',
            'token' => 'ABC123',
            'is_eligible' => true,
            'status' => 'SUDAH',
            'candidate_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->post(route('voting.session.end'));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $fresh = $user->fresh();

        $this->assertNull($fresh->token);
        $this->assertEquals(0, $fresh->is_eligible);
        $this->assertEquals('BELUM', $fresh->status);
        $this->assertNull($fresh->candidate_id);
    }
}
