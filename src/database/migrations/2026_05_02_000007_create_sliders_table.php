<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow_tr')->nullable();
            $table->string('title_tr');
            $table->string('title_en')->nullable();
            $table->text('subtitle_tr')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('image');
            $table->string('cta_text_tr')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('overlay_color', 7)->default('#0B295C');
            $table->tinyInteger('overlay_opacity')->default(40);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
