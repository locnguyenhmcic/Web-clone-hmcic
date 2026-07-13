<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_news_by_search_and_category_and_keeps_published_only(): void
    {
        $categoryA = Category::query()->create([
            'name' => 'Quy hoach',
            'slug' => 'quy-hoach',
        ]);

        $categoryB = Category::query()->create([
            'name' => 'Thong bao',
            'slug' => 'thong-bao',
        ]);

        $matched = $this->createPublishedPost('Thong tin quy hoach do thi moi nhat');
        $matched->categories()->attach($categoryA->id);

        $differentTitle = $this->createPublishedPost('Bao cao tien do xay dung quy 2');
        $differentTitle->categories()->attach($categoryA->id);

        $differentCategory = $this->createPublishedPost('Quy hoach giao thong lien vung');
        $differentCategory->categories()->attach($categoryB->id);

        $draft = $this->createPost('Du thao quy hoach phan khu', 'draft');
        $draft->categories()->attach($categoryA->id);

        $response = $this->getJson('/api/news?search=quy%20hoach&category_id=' . $categoryA->id . '&per_page=10');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $matched->id);
        $response->assertJsonPath('data.0.title', $matched->title);
        $response->assertJsonPath('pagination.total', 1);
    }

    public function test_it_returns_only_categories_that_have_published_posts(): void
    {
        $publishedCategory = Category::query()->create([
            'name' => 'Thong bao',
            'slug' => 'thong-bao',
        ]);

        $draftOnlyCategory = Category::query()->create([
            'name' => 'Noi bo',
            'slug' => 'noi-bo',
        ]);

        $published = $this->createPublishedPost('Thong bao khan');
        $published->categories()->attach($publishedCategory->id);

        $draft = $this->createPost('Thong tin noi bo', 'draft');
        $draft->categories()->attach($draftOnlyCategory->id);

        $response = $this->getJson('/api/news/categories');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $publishedCategory->id);
        $response->assertJsonPath('data.0.name', $publishedCategory->name);
    }

    public function test_it_returns_published_news_detail_with_related_posts(): void
    {
        $category = Category::query()->create([
            'name' => 'Tin chuyen de',
            'slug' => 'tin-chuyen-de',
        ]);

        $post = $this->createPublishedPost('Bao cao danh gia chat luong cong trinh');
        $post->update([
            'content' => '<p>Noi dung chi tiet</p><img src="/storage/uploads/example.jpg" alt="Anh">',
        ]);
        $post->categories()->attach($category->id);

        $related = $this->createPublishedPost('Thong tin cap nhat cung chuyen muc');
        $related->categories()->attach($category->id);

        $otherCategory = Category::query()->create([
            'name' => 'Khac',
            'slug' => 'khac',
        ]);
        $unrelated = $this->createPublishedPost('Tin khong lien quan');
        $unrelated->categories()->attach($otherCategory->id);

        $response = $this->getJson('/api/news/' . $post->slug);

        $response->assertOk();
        $response->assertJsonPath('data.slug', $post->slug);
        $response->assertJsonPath('data.title', $post->title);
        $response->assertJsonPath('data.content', $post->content);
        $response->assertJsonPath('data.related_posts.0.id', $related->id);
        $response->assertJsonMissingPath('data.related_posts.1');
    }

    private function createPublishedPost(string $title): Post
    {
        return $this->createPost($title, 'published', now());
    }

    private function createPost(string $title, string $status, $publishedAt = null): Post
    {
        return Post::query()->create([
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::lower(Str::random(5)),
            'excerpt' => 'Mo ta ngan',
            'content' => '<p>Noi dung</p>',
            'status' => $status,
            'published_at' => $publishedAt,
        ]);
    }
}
