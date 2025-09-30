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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('email')->unique()->nullable();

            $table->string('username')->unique();
            $table->string('password');

            $table->string('profile_photo_path', 2048)->nullable();

            $table->json('data')->nullable();

            $table->enum('role', ['owner', 'admin', 'manager', 'staff', 'client'])->default('staff');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for faster lookup
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
