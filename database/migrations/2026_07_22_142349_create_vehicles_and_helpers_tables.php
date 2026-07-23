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
        // 1. Create vehicles table
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique();
            $table->string('dut')->nullable();
            $table->string('renavan')->nullable();
            $table->string('brand_model');
            $table->string('color');
            $table->integer('year_fabrication');
            $table->integer('year_model');
            $table->boolean('is_outsourced')->default(false);
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('Ativo'); // Ativo, Manutenção, Inativo
            $table->timestamps();
        });

        // 2. Create helpers table
        Schema::create('helpers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Create vehicle_checkins table
        Schema::create('vehicle_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('driver_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('helper_id')->nullable()->constrained('helpers')->nullOnDelete();
            $table->foreignId('helper_2_id')->nullable()->constrained('helpers')->nullOnDelete();
            $table->integer('odometer_start');
            $table->integer('odometer_end')->nullable();
            $table->boolean('check_tires')->default(true);
            $table->boolean('check_brakes')->default(true);
            $table->boolean('check_lights')->default(true);
            $table->boolean('check_oil')->default(true);
            $table->boolean('check_wipers')->default(true);
            $table->integer('num_drums')->default(0);
            $table->boolean('is_impeditivo')->default(false);
            $table->text('obs')->nullable();
            $table->date('check_date');
            $table->dateTime('checkout_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_checkins');
        Schema::dropIfExists('helpers');
        Schema::dropIfExists('vehicles');
    }
};
