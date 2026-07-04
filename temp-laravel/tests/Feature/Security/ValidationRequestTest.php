<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_returns_validation_errors_for_invalid_payload(): void
    {
        $token = 'csrf-token';

        $response = $this
            ->withMiddleware()
            ->withSession(['_token' => $token])
            ->post(route('contact.submit'), [
                '_token' => $token,
                'name' => '',
                'email' => 'invalid-email',
                'subject' => '',
                'message' => 'short',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }
}