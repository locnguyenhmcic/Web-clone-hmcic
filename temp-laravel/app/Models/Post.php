<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\HtmlSanitizerService;

class Post extends Model
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
        ];
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => app(HtmlSanitizerService::class)->sanitize($value),
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => app(HtmlSanitizerService::class)->sanitize($value),
        );
    }

    protected static function booted(): void
    {
        static::saved(function (self $post) {
            \Illuminate\Support\Facades\Cache::tags('homepage')->flush();
        });

        static::deleted(function (self $post) {
            \Illuminate\Support\Facades\Cache::tags('homepage')->flush();
        });
    }
}
