<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_id')->nullable()->constrained('modules')->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();

            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('type')->nullable()->comment('e.g., subscription, one-time, etc.');

            $table->decimal('amount', 32, 2)->nullable();
            $table->decimal('discount', 32, 2)->nullable();
            $table->decimal('total_amount', 32, 2)->nullable();

            $table->string('code')->nullable()->comment('Unique code for the factor');
            $table->text('description')->nullable()->comment('Description or notes about the factor');

            $table->enum('status', ['paid', 'pending', 'refunded', 'failed', 'canceled'])->default('pending');
            $table->timestamps();

            // Indexes for faster lookup
            $table->index('plan_id');
            $table->index('module_id');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factors');
    }
};
