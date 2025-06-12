<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\User;
use App\Candidate;

class VotingSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Gate::define('manage-users', fn () => true);
    }

    public function test_start_voting_session_generates_tokens()
    {
        $admin = factory(User::class)->create([
            'roles' => json_encode(['ADMIN']),
            'name' => 'Admin User Panjang Banget Validasi',
            'nik' => '3201021503980001',
            'phone' => '081234567891',
            'address' => 'Alamat Admin Panjang Validasi Banget'
        ]);

        $eligibleUser = factory(User::class)->create([
            'is_eligible' => true,
            'roles' => json_encode(['VOTER']),
            'name' => 'Voter Panjang Banget Validasi',
            'nik' => '3201021503980002',
            'phone' => '081234567892',
            'address' => 'Alamat Voter Panjang Validasi Banget'
        ]);

        $response = $this->actingAs($admin)->post(route('voting.session'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Sesi Voting Dimulai dan Token Telah Dibuat');

        $this->assertDatabaseMissing('users', [
            'id' => $eligibleUser->id,
            'token' => null,
        ]);
    }

    public function test_end_voting_session_resets_user_data()
    {
        $admin = factory(User::class)->create([
            'roles' => json_encode(['ADMIN']),
            'name' => 'Admin Lain Valid Panjang',
            'nik' => '3201021503980003',
            'phone' => '081234567893',
            'address' => 'Alamat Admin Valid Panjang'
        ]);

        $candidate = factory(Candidate::class)->create();

        $voter = factory(User::class)->create([
            'roles' => json_encode(['VOTER']),
            'is_eligible' => true,
            'token' => 'ABC123',
            'status' => 'SUDAH',
            'candidate_id' => $candidate->id,
            'name' => 'Voter Panjang Validasi Banget',
            'nik' => '3201021503980004',
            'phone' => '081234567894',
            'address' => 'Alamat Voter Panjang Validasi Sekali'
        ]);

        $response = $this->actingAs($admin)->post(route('voting.session.end'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Sesi Voting telah diakhiri, semua data voting sudah direset.');

        $this->assertDatabaseHas('users', [
            'id' => $voter->id,
            'token' => null,
            'is_eligible' => false,
            'status' => 'BELUM',
            'candidate_id' => null
        ]);
    }
}
