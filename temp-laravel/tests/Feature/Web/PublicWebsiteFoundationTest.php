<?php

namespace Tests\Feature\Web;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicWebsiteFoundationTest extends TestCase
{
    public function test_homepage_renders_public_website_shell(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page): Assert => $page
            ->component('Home')
            ->where('foundation.version', 'public-v1')
            ->where('foundation.has_header', true)
            ->where('foundation.has_navigation', true)
            ->where('foundation.has_footer', true)
            ->where('foundation.responsive_ready', true)
        );
    }

    public function test_public_navigation_routes_are_available(): void
    {
        $this->get('/')->assertOk();
        $this->get('/tin-tuc')->assertOk();
        $this->get('/du-an')->assertOk();
        $this->get('/lien-he')->assertOk();
    }
}
