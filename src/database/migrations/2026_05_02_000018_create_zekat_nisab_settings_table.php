<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zekat_nisab_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('gold_price_per_gram', 10, 2);
            $table->decimal('silver_price_per_gram', 10, 2);
            $table->decimal('nisab_gold_grams', 8, 2)->default(80.18);
            $table->decimal('nisab_silver_grams', 8, 2)->default(560);
            $table->date('updated_for_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zekat_nisab_settings');
    }
};
