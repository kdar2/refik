<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_tr');
            $table->string('title_en')->nullable();
            $table->text('excerpt_tr')->nullable();
            $table->longText('content_tr');
            $table->longText('content_en')->nullable();
            $table->foreignId('post_category_id')->nullable()->constrained();
            $table->foreignId('author_id')->nullable()->constrained('users');
            $table->string('cover_image');
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('seo')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
