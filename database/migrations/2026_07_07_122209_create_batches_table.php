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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code')->unique();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->decimal('input_weight', 12, 2)->default(0.00); // total raw weight processed
            $table->decimal('output_tallow_weight', 12, 2)->default(0.00); // tallow produced in kg
            $table->decimal('output_meal_weight', 12, 2)->default(0.00); // meal produced in kg
            $table->decimal('yield_percentage', 5, 2)->default(0.00); // (tallow + meal) / input * 100
            $table->string('status')->default('Em Processamento'); // Em Processamento, Concluído
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
