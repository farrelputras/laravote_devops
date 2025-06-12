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

        $user = factory(User::class)->create([
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'name' => 'Raden Mas Sutrisno Santosa Jaya',
            'address' => 'Jl. Pancasila No. 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('secret123'),
            'roles' => '["VOTER"]',
            'token' => 'ABC123',
        ]);

        Candidate::create([
            'nama_ketua' => 'Ahmad Faisal',
            'nama_wakil' => 'Rina Kumala',
            'visi' => 'Mewujudkan masyarakat adil dan makmur',
            'misi' => 'Membangun infrastruktur merata di seluruh wilayah',
            'program_kerja' => '1. Pendidikan gratis\n2. Kesehatan terjangkau\n3. UMKM naik kelas',
            'photo_paslon' => 'paslon/fake.jpg',
        ]);

        Gate::define('manage-pilihan', fn() => true);
        $this->actingAs($user);

        $response = $this->get(route('candidates.pilihan'));

        $response->assertStatus(200);
    }

    public function test_user_cannot_vote_without_token()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'name' => 'Raden Mas Sutrisno Santosa Jaya',
            'address' => 'Jl. Merdeka Timur No. 12',
            'email' => 'user2@example.com',
            'password' => bcrypt('secret123'),
            'roles' => '["VOTER"]',
            'token' => null,
        ]);

        Gate::define('manage-pilihan', fn() => true);
        $this->actingAs($user);

        $response = $this->get(route('candidates.pilihan'));

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_user_can_successfully_vote()
    {
        $candidate = Candidate::create([
            'nama_ketua' => 'Budi Gunawan',
            'nama_wakil' => 'Siti Aminah',
            'visi' => 'Transparansi dan akuntabilitas pemerintahan',
            'misi' => 'Reformasi birokrasi dan digitalisasi layanan publik',
            'program_kerja' => '1. Pelayanan publik berbasis digital\n2. Anti korupsi\n3. Pendidikan vokasi',
            'photo_paslon' => 'paslon/fake.jpg',
        ]);

        $user = factory(User::class)->create([
            'nik' => '3201021503980000',
            'phone' => '081234567890',
            'name' => 'Raden Mas Sutrisno Santosa Jaya',
            'address' => 'Jl. Proklamasi No. 45',
            'email' => 'user3@example.com',
            'password' => bcrypt('secret123'),
            'roles' => '["VOTER"]',
            'token' => 'ABC123',
            'status' => 'BELUM',
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
