<?php

namespace Tests\Feature\Security;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class XssProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_content_is_sanitized_before_storage(): void
    {
        $user = User::factory()->create();

        $post = Post::query()->create([
            'title' => 'Security hardening',
            'slug' => Str::slug('Security hardening'),
            'content' => '<p>Hello</p><script>alert(1)</script><img src="javascript:alert(1)" onerror="alert(2)">',
            'status' => 'draft',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $this->assertStringNotContainsString('<script', $post->content);
        $this->assertStringNotContainsString('onerror=', $post->content);
        $this->assertStringNotContainsString('javascript:', $post->content);
    }
}