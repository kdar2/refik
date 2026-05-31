<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_tr');
            $table->string('title_en')->nullable();
            $table->text('subtitle_tr')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->longText('description_tr');
            $table->longText('description_en')->nullable();

            $table->foreignId('category_id')->constrained('campaign_categories');
            $table->foreignId('country_id')->nullable()->constrained('countries');

            $table->string('cover_image');
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();

            $table->decimal('goal_amount', 15, 2)->nullable();
            $table->decimal('raised_amount', 15, 2)->default(0);
            $table->char('currency', 3)->default('TRY');
            $table->integer('donor_count')->default(0);

            $table->boolean('zakat_eligible')->default(false);
            $table->boolean('sadaka_eligible')->default(false);
            $table->boolean('fitre_eligible')->default(false);
            $table->boolean('kurban_eligible')->default(false);

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_emergency')->default(false);
            $table->boolean('is_active')->default(true);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->json('seo')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_featured', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
