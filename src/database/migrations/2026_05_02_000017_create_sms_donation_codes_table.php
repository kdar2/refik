<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sms_donation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('label_tr');
            $table->string('label_en');
            $table->string('short_code', 10);
            $table->string('keyword')->nullable();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('TRY');
            $table->string('qr_code_path')->nullable();
            $table->text('description_tr')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_donation_codes');
    }
};
