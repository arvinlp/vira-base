<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('plan_id')->constrained('plans')->onDelete('set null')->onUpdate('cascade');

            $table->string('name_fa')->nullable();
            $table->string('name_en')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();

            $table->integer('country_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('county_id')->nullable();
            $table->integer('city_id')->nullable();

            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');

            $table->timestamps();
            $table->softDeletes();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
