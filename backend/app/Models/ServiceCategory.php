<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'slug',
		'parent_id',
	];

	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function newsArticles(): BelongsToMany
	{
		return $this->belongsToMany(NewsArticle::class, 'news_article_service_category');
	}
}

