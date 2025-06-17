<?php

namespace Tests\Feature;

use App\User;
use App\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Define gate so all tests allow manage-candidates
        Gate::define('manage-candidates', fn ($user = null) => true);
    }

    public function test_index_returns_candidates_view()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        $response = $this->get(route('candidates.index'));

        $response->assertStatus(200);
        $response->assertViewIs('candidates.index');
    }

    public function test_create_returns_create_view()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        $response = $this->get(route('candidates.create'));

        $response->assertStatus(200);
        $response->assertViewIs('candidates.create');
    }

    public function test_store_candidate()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        Storage::fake('public');

        $data = [
            'nama_ketua' => 'Ketua Example',
            'nama_wakil' => 'Wakil Example',
            'visi' => 'Visi yang cukup panjang untuk validasi.',
            'misi' => 'Misi yang cukup panjang untuk validasi.',
            'program_kerja' => 'Program kerja yang panjang.',
            'photo_paslon' => UploadedFile::fake()->image('photo.png', 600, 400)
                ->size(1500) // Size in KB
                ->mimeType('image/png'),
        ];

        $response = $this->post(route('candidates.store'), $data);

        $response->assertRedirect(route('candidates.create'));
        $this->assertDatabaseHas('candidates', [
            'nama_ketua' => 'Ketua Example',
            'nama_wakil' => 'Wakil Example',
        ]);
        // Make sure file is stored
        Storage::disk('public')->assertExists(Candidate::first()->photo_paslon);
    }

    public function test_edit_candidate()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        $candidate = factory(Candidate::class)->create();

        $response = $this->get(route('candidates.edit', $candidate->id));
        $response->assertStatus(200);
        $response->assertViewIs('candidates.edit');
    }

    public function test_update_candidate()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        Storage::fake('public');
        $candidate = factory(Candidate::class)->create();

        $updateData = [
            'nama_ketua' => 'Ketua Update',
            'nama_wakil' => 'Wakil Update',
            'visi' => 'Visi updated panjang.',
            'misi' => 'Misi updated panjang.',
            'program_kerja' => 'Program kerja updated.',
            'photo_paslon' => UploadedFile::fake()->image('photo_new.png', 600, 400)
                ->size(1500) // Size in KB
                ->mimeType('image/png'),
        ];

        $response = $this->put(route('candidates.update', $candidate->id), $updateData);

        $response->assertRedirect(route('candidates.index'));
        $this->assertDatabaseHas('candidates', [
            'id' => $candidate->id,
            'nama_ketua' => 'Ketua Update'
        ]);
    }

    public function test_destroy_candidate()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        $candidate = factory(Candidate::class)->create();

        $response = $this->delete(route('candidates.destroy', $candidate->id));
        $response->assertRedirect(route('candidates.index'));
        $this->assertDatabaseMissing('candidates', [
            'id' => $candidate->id
        ]);
    }
}
