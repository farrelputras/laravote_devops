<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Gate::define('manage-users', fn () => true);
    }

    public function test_user_create_form_displayed()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);

        $response = $this->actingAs($admin)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    public function test_user_can_be_stored()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Nama User Panjang Sekali Valid',
            'nik' => '3201021503989999',
            'phone' => '081234567891',
            'address' => 'Alamat Sangat Panjang dan Valid',
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('users.create'));
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_user_can_be_updated()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->put(route('users.update', $user->id), [
            'name' => 'Nama Diupdate Valid Banget',
            'nik' => '3201021503988888',
            'phone' => '081234567892',
            'address' => 'Alamat Update Sangat Panjang',
            'email' => 'update@example.com'
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'update@example.com']);
    }

    public function test_user_can_be_deleted()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_toggle_eligible()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);
        $user = factory(User::class)->create([
            'is_eligible' => false,
            'token' => null
        ]);

        $response = $this->actingAs($admin)->put(route('users.toggleEligible', $user->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_eligible' => true
        ]);
    }

    public function test_show_returns_empty_success()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);
        $response = $this->actingAs($admin)->get(route('users.show', 1));
        $response->assertStatus(200);
    }

    public function test_user_index_returns_view_for_non_ajax()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_user_index_returns_json_for_ajax()
    {
        $admin = factory(User::class)->create(['roles' => json_encode(['ADMIN'])]);
        factory(User::class)->create(['status' => 'sudah']);
        factory(User::class)->create(['status' => 'belum']);

        $response = $this->actingAs($admin)->get(route('users.index', ['draw' => 1]), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data', 'draw', 'recordsTotal', 'recordsFiltered'
        ]);
    }

    // public function test_user_delete_all()
    // {
    //     $admin = factory(User::class)->create([
    //     'roles' => json_encode(['ADMIN']),
    //     ]);

    //     $users = factory(User::class, 5)->create([
    //         'roles' => json_encode(['VOTER']),
    //     ]);

    //     $this->actingAs($admin);

    //     $response = $this->delete(route('users.destroy', ['user' => 'all']));

    //     $response->assertRedirect(route('users.index'));
    //     $response->assertSessionHas('status', 'Semua user berhasil dihapus');

    //     $this->assertEquals(1, \App\User::count());
    //     $this->assertEquals($admin->id, \App\User::first()->id);
    // }

}
