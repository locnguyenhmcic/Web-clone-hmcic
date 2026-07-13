<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\HtmlSanitizerService;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail_id',
        'status',
        'published_at',
        'featured',
        'seo_title',
        'seo_description',
        'canonical_url',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'featured' => 'boolean',
        ];
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted(): void
    {
        static::saved(function (self $project) {
            static::flushHomepageCache();
        });

        static::deleted(function (self $project) {
            static::flushHomepageCache();
        });
    }

    protected static function flushHomepageCache(): void
    {
        $cacheStore = \Illuminate\Support\Facades\Cache::getStore();

        if (method_exists($cacheStore, 'tags')) {
            \Illuminate\Support\Facades\Cache::tags('homepage')->flush();

            return;
        }

        \Illuminate\Support\Facades\Cache::forget('homepage_data');
    }
}
