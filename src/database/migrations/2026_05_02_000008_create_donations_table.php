<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('campaign_id')->nullable()->constrained();

            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->string('tckn', 11)->nullable();
            $table->string('company_name')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('tax_no')->nullable();

            $table->decimal('amount', 15, 2);
            $table->char('currency', 3)->default('TRY');
            $table->decimal('amount_try', 15, 2);

            $table->enum('type', ['general', 'zakat', 'fitre', 'sadaka', 'kurban', 'adak', 'kefaret']);
            $table->enum('frequency', ['one_time', 'monthly', 'quarterly', 'yearly'])->default('one_time');
            $table->date('next_charge_at')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->boolean('is_corporate')->default(false);

            $table->string('intention')->nullable();
            $table->string('intention_for')->nullable();
            $table->text('message')->nullable();

            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'sms', 'crypto']);
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('payment_provider')->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->json('payment_response')->nullable();

            $table->boolean('certificate_requested')->default(false);
            $table->string('certificate_path')->nullable();

            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['payment_status', 'created_at']);
            $table->index(['campaign_id', 'payment_status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
