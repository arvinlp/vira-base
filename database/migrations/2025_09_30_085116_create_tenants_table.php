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
            $table->id();

            $table->foreignId('plan_id')
                ->nullable()
                ->constrained('plans')
                ->onDelete('set null')
                ->cascadeOnUpdate();

            $table->string('name_fa')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();

            $table->integer('country_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('county_id')->nullable();
            $table->integer('city_id')->nullable();

            $table->integer('domain_quantity')->nullable();
            $table->integer('subdomain_quantity')->nullable();

            $table->decimal('storage_size', 10, 2)->nullable()->comment('in GB')->default(1);
            $table->decimal('bandwidth', 10, 2)->nullable()->comment('in GB')->default(1);

            $table->json('data')->nullable();

            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for faster lookup
            $table->index('plan_id');
            $table->index('status');
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
