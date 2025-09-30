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
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();

            $table->string('username');
            $table->string('password');

            $table->string('profile_photo_path', 2048)->nullable();

            $table->json('data')->nullable();

            $table->enum('role', ['super_admin', 'admin', 'staff'])->default('staff');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add owner_id to tenants table
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->foreignId('owner_id')
                    ->constrained('tenant_users')
                    ->onDelete('set null')
                    ->onUpdate('cascade')
                    ->nullable()
                    ->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
