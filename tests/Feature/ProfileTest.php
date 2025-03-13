<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Test City',
            'telephone' => '0612345678',
            'status' => 1,
        ]);




        // Authenticate as the user
        $this->actingAs($this->user);
    }

    public function test_profile_page_is_displayed(): void
    {

        $response = $this->get('fixi-plus/profile');
        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {

        $response = $this->patch('fixi-plus/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'telephone' => '0612345678',
            'ville'=>'marrakech',
        ]);

        $response
            ->assertSessionHasNoErrors();

        $this->user->refresh();

        $this->assertSame('Test User', $this->user->name);
        $this->assertSame('test@example.com', $this->user->email);
        $this->assertNull($this->user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {

        $response = $this->patch('fixi-plus/profile', [
            'name' => 'Test User',
            'email' => $this->user->email,
            'telephone' => '0612345678',
            'ville'=>'marrakech',

        ]);

        $response
            ->assertSessionHasNoErrors();

        $this->assertNotNull($this->user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {

        $response = $this->delete('fixi-plus/profile', [
            'password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors();

        $this->assertGuest();
        $this->assertNull($this->user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {

        $response = $this->from('fixi-plus/profile')
            ->delete('fixi-plus/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('fixi-plus/profile');

        $this->assertNotNull($this->user->fresh());
    }
}
