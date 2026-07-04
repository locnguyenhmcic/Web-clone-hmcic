<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('news_articles', function (Blueprint $table): void {
			$table->id();
			$table->string('title');
			$table->string('slug')->unique();
			$table->longText('content');
			$table->string('thumbnail_path')->nullable();
			$table->enum('status', ['draft', 'published', 'hidden'])->default('draft');
			$table->timestamp('published_at')->nullable();
			$table->string('seo_title')->nullable();
			$table->text('seo_description')->nullable();
			$table->string('canonical_url')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->index('status');
			$table->index('published_at');
		});

		Schema::create('news_article_service_category', function (Blueprint $table): void {
			$table->foreignId('news_article_id')->constrained('news_articles')->cascadeOnDelete();
			$table->foreignId('service_category_id')->constrained('service_categories')->cascadeOnDelete();

			$table->primary(['news_article_id', 'service_category_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('news_article_service_category');
		Schema::dropIfExists('news_articles');
	}
};

