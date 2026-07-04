<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('seo_title')->nullable()->after('published_at');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('canonical_url')->nullable()->after('seo_description');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'seo_description', 'canonical_url']);
        });
    }
};
