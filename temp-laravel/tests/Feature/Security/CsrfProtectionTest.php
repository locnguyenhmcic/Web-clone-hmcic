<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsrfProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_route_is_protected_by_csrf_middleware(): void
    {
        $route = app('router')->getRoutes()->getByName('contact.submit');

        $this->assertNotNull($route);
        $this->assertContains('web', $route->gatherMiddleware());
    }

    public function test_contact_submission_accepts_valid_csrf_token(): void
    {
        $token = 'csrf-token';

        $response = $this
            ->withMiddleware()
            ->withSession(['_token' => $token])
            ->post(route('contact.submit'), [
                '_token' => $token,
                'name' => 'Security Tester',
                'email' => 'tester@example.com',
                'subject' => 'Valid token',
                'message' => 'This request should pass with valid CSRF token.',
            ]);

        $response->assertOk();
        $response->assertJsonPath('message', 'Contact submitted successfully.');
    }
}