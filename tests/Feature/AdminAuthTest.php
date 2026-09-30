<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/news')->assertRedirect('/login');
        $this->post('/admin/faqs', [])->assertRedirect('/login');
    }

    public function test_active_user_can_sign_in_and_out(): void
    {
        $user = User::factory()->create(['password' => 'Secret123']);

        $this->post('/login', ['email' => $user->email, 'password' => 'Secret123'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_login_at);

        $this->get('/admin/dashboard')->assertOk();

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_wrong_password_and_inactive_accounts_are_rejected(): void
    {
        $user = User::factory()->create(['password' => 'Secret123']);
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $inactive = User::factory()->inactive()->create(['password' => 'Secret123']);
        $this->post('/login', ['email' => $inactive->email, 'password' => 'Secret123'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_deactivated_user_is_signed_out_on_next_request(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $user->update(['status' => User::STATUS_INACTIVE]);

        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_passwords_are_hashed(): void
    {
        $user = User::factory()->create(['password' => 'Secret123']);

        $this->assertNotSame('Secret123', $user->getAttributes()['password']);
        $this->assertTrue(password_verify('Secret123', $user->password));
    }
}
