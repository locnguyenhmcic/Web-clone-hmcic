<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Filament\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get(route('filament.admin.auth.login'));

        $response->assertOk();
    }

    public function test_guest_is_redirected_to_admin_login_page_when_accessing_admin_dashboard(): void
    {
        $response = $this->get(route('filament.admin.pages.dashboard'));

        $response->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_user_can_login_to_filament_admin_with_remember_me(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $loginPageResponse = $this->get(route('filament.admin.auth.login'));
        $loginPageResponse->assertSee('remember', escape: false);

        Livewire::test(Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', 'password')
            ->set('data.remember', true)
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_logout_from_filament_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('filament.admin.auth.logout'));

        $response->assertRedirect(route('filament.admin.auth.login'));
        $this->assertGuest();
    }

    public function test_session_timeout_and_auth_middleware_are_configured(): void
    {
        $this->assertSame(120, config('session.lifetime'));

        $dashboardRoute = app('router')->getRoutes()->getByName('filament.admin.pages.dashboard');

        $this->assertNotNull($dashboardRoute);
        $this->assertContains(Authenticate::class, $dashboardRoute->gatherMiddleware());
    }
}
