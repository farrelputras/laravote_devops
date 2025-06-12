<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

class ChoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_token_can_see_pilihan_page()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(['token' => 'ABC123']);

        Gate::define('manage-pilihan', fn() => true);

        $this->actingAs($user);

        $response = $this->get(route('candidates.pilihan'));

        $response->assertStatus(200);
    }

    public function test_user_cannot_vote_without_token()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(['token' => null]);

        Gate::define('manage-pilihan', fn() => true);
        $this->actingAs($user);

        $response = $this->get(route('candidates.pilihan'));

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_user_can_successfully_vote()
    {
        $user = factory(User::class)->create([
            'token' => 'ABC123',
            'status' => 'BELUM'
        ]);

        // Tanpa Candidate::factory()
        $candidate = \App\Candidate::create([
            'name' => 'Contoh Kandidat',
            'visi' => 'Visi A',
            'misi' => 'Misi A',
        ]);

        Gate::define('manage-pilihan', fn() => true);
        $this->actingAs($user);

        $response = $this->put(route('users.pilih', $user->id), [
            'token' => 'ABC123',
            'candidate_id' => $candidate->id,
        ]);

        $response->assertRedirect(route('candidates.pilihan'));
        $response->assertSessionHas('status');

        $this->assertEquals('SUDAH', $user->fresh()->status);
        $this->assertEquals($candidate->id, $user->fresh()->candidate_id);
    }
}
