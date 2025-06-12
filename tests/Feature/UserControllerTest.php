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
        $this->assertDeleted($user);
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
}
