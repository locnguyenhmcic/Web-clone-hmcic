<?php

namespace Tests\Feature\Security;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthorizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('filament.admin.pages.dashboard'));

        $response->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_authenticated_admin_is_allowed_to_manage_posts_by_policy(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin);

        $this->assertTrue(Gate::allows('viewAny', Post::class));
        $this->assertTrue(Gate::allows('create', Post::class));
    }
}