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
        Schema::create('tenant_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();

            // تاریخ شروع و پایان اشتراک
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // محدودیت کاربران و منابع (در صورت نیاز)
            $table->integer('max_users')->nullable(); // از پلن می‌تونه override بشه
            $table->integer('max_storage_mb')->nullable(); // مثال محدودیت حجم ذخیره‌سازی

            // امکان ذخیره ماژول‌های فعال فقط برای این tenant (optional)
            $table->json('modules')->nullable();

            // وضعیت billing/renewal
            $table->enum('status', ['active', 'expired', 'cancelled', 'trial'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_plans');
    }
};
