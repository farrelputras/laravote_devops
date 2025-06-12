<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\User;
use App\Candidate;
use Illuminate\Support\Str;

class ChoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Gate::define('manage-pilihan', fn () => true);
    }

    public function test_user_with_token_can_see_pilihan_page()
    {
        $user = factory(User::class)->create([
            'token' => 'ABC123',
            'roles' => json_encode(['VOTER']),
            'name' => 'Nama Panjang Banget Biar Lolos Validasi',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat lengkap dan panjang'
        ]);

        factory(Candidate::class)->create([
            'nama_ketua' => 'Ketua Panjang Validasi',
            'nama_wakil' => 'Wakil Panjang Validasi',
            'visi' => 'Visi yang sangat panjang dan lengkap',
            'misi' => 'Misi yang sangat panjang dan lengkap',
            'program_kerja' => 'Program kerja yang sangat panjang dan lengkap',
            'photo_paslon' => 'foto.jpg'
        ]);

        $response = $this->actingAs($user)->get(route('candidates.pilihan'));

        $response->assertStatus(200);
        $response->assertViewIs('pilihan.index');
    }

    public function test_user_cannot_vote_without_token()
    {
        $user = factory(User::class)->create([
            'token' => 'ABC123',
            'roles' => json_encode(['VOTER']),
            'name' => 'Nama Validasi Panjang Sekali',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat Panjang Sekali Biar Valid'
        ]);

        $response = $this->actingAs($user)->put(route('users.pilih', $user->id), [
            'candidate_id' => 1
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Anda Tidak Berhak Memilih');
    }

    public function test_user_can_successfully_vote()
    {
        $user = factory(User::class)->create([
            'token' => 'ABC123',
            'roles' => json_encode(['VOTER']),
            'status' => 'BELUM',
            'name' => 'User Untuk Validasi Nama Panjang',
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'address' => 'Alamat Panjang Validasi Tes'
        ]);

        $candidate = factory(Candidate::class)->create([
            'nama_ketua' => 'Ketua Sangat Valid',
            'nama_wakil' => 'Wakil Juga Valid',
            'visi' => 'Visi Panjang Beneran Nih',
            'misi' => 'Misi Validasi Sangat Panjang',
            'program_kerja' => 'Program kerja sangat sangat valid',
            'photo_paslon' => 'foto.jpg'
        ]);

        $response = $this->actingAs($user)->put(route('users.pilih', $user->id), [
            'token' => 'ABC123',
            'candidate_id' => $candidate->id
        ]);

        $response->assertRedirect(route('candidates.pilihan'));
        $response->assertSessionHas('status', 'Pilihan Anda berhasil dicatat.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'SUDAH',
            'candidate_id' => $candidate->id
        ]);
    }
}
