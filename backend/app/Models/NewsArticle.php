<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsArticle extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $fillable = [
		'title',
		'slug',
		'content',
		'thumbnail_path',
		'status',
		'published_at',
		'seo_title',
		'seo_description',
		'canonical_url',
	];

	protected $casts = [
		'published_at' => 'datetime',
	];

	public function serviceCategories(): BelongsToMany
	{
		return $this->belongsToMany(ServiceCategory::class, 'news_article_service_category');
	}
}

