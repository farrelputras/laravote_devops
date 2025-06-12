<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VotingSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_voting_session_generates_tokens()
    {
        Gate::define('manage-users', fn() => true);

        $users = factory(User::class, 2)->create([
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'name' => 'Dewi Sri Hartati Dewantara',
            'address' => 'Jl. Cinta Damai No. 10',
            'email' => Str::random(6) . '@example.com',
            'password' => bcrypt('password123'),
            'roles' => '["VOTER"]',
            'is_eligible' => true,
        ]);

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

        $candidate = Candidate::create([
            'nama_ketua' => 'Cakra Adipati',
            'nama_wakil' => 'Fitri Handayani',
            'visi' => 'Inovasi dan kemajuan daerah',
            'misi' => 'Pendidikan digital untuk generasi muda',
            'program_kerja' => '1. Beasiswa pendidikan\n2. Internet desa\n3. Inkubator startup',
            'photo_paslon' => 'paslon/fake.jpg',
        ]);

        $user = factory(User::class)->create([
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'name' => 'Ki Hadjar Dewantara Muda',
            'address' => 'Jl. Revolusi Mental No. 88',
            'email' => 'voter@example.com',
            'password' => bcrypt('password321'),
            'roles' => '["VOTER"]',
            'token' => 'ABC123',
            'is_eligible' => true,
            'status' => 'SUDAH',
            'candidate_id' => $candidate->id,
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
