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
            'name' => 'Admin User Yang Panjang',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat Panjang Validasi'
        ]);

        $eligibleUser = factory(User::class)->create([
            'is_eligible' => true,
            'roles' => json_encode(['VOTER']),
            'name' => 'Eligible Voter Panjang',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat Panjang'
        ]);

        $response = $this->actingAs($admin)->post(route('voting.session'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Sesi Voting Dimulai dan Token Telah Dibuat');

        $this->assertDatabaseMissing('users', ['token' => null, 'id' => $eligibleUser->id]);
    }

    public function test_end_voting_session_resets_user_data()
    {
        $admin = factory(User::class)->create([
            'roles' => json_encode(['ADMIN']),
            'name' => 'Admin Lagi',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat Admin Panjang'
        ]);

        $candidate = factory(Candidate::class)->create([
            'nama_ketua' => 'Ketua Validasi',
            'nama_wakil' => 'Wakil Validasi',
            'visi' => 'Visi Panjang',
            'misi' => 'Misi Panjang',
            'program_kerja' => 'Program Panjang',
            'photo_paslon' => 'foto.jpg'
        ]);

        $voter = factory(User::class)->create([
            'roles' => json_encode(['VOTER']),
            'is_eligible' => true,
            'token' => 'ABC123',
            'status' => 'SUDAH',
            'candidate_id' => $candidate->id,
            'name' => 'Voter Panjang Sekali',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat Voter Valid'
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
