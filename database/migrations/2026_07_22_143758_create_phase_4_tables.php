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
        // 1. Create inventory_items table
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('sku')->unique()->nullable();
            $table->decimal('stock', 10, 2)->default(0.00);
            $table->decimal('min_stock', 10, 2)->default(0.00);
            $table->string('unit')->default('UN');
            $table->timestamps();
        });

        // 2. Create inventory_transactions table
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('type'); // Entrada, Saída
            $table->decimal('quantity', 10, 2);
            $table->string('description')->nullable();
            $table->dateTime('transaction_date');
            $table->timestamps();
        });

        // 3. Add inventory_item_id and quantity to operational_costs table
        Schema::table('operational_costs', function (Blueprint $table) {
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->decimal('quantity', 10, 2)->nullable();
        });

        // 4. Create tallow_quality_certificates table
        Schema::create('tallow_quality_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->date('analysis_date');
            $table->date('shipping_date');
            $table->string('production_date');
            $table->string('expiry_info');
            $table->string('result_aspect');
            $table->string('result_acidity');
            $table->string('result_impurities');
            $table->string('result_odor');
            $table->string('result_moisture');
            $table->string('vehicle_plate');
            $table->string('carrier_name');
            $table->string('invoice_number');
            $table->string('seal_number');
            $table->boolean('inspected_clean_external')->default(true);
            $table->boolean('inspected_clean_internal')->default(true);
            $table->boolean('inspected_dry_internal')->default(true);
            $table->boolean('is_released')->default(true);
            $table->string('qa_responsible');
            $table->string('technical_responsible');
            $table->timestamps();
        });

        // 5. Create meal_quality_certificates table
        Schema::create('meal_quality_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->date('analysis_date');
            $table->integer('revisao_number')->default(1);
            $table->string('invoice_number');
            $table->decimal('weight', 10, 2);
            $table->string('vehicle_plate');
            $table->string('driver_name');
            $table->string('driver_cpf');
            $table->string('seal_number');
            $table->text('non_conformities')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->text('verification')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_quality_certificates');
        Schema::dropIfExists('tallow_quality_certificates');

        Schema::table('operational_costs', function (Blueprint $table) {
            $table->dropForeign(['inventory_item_id']);
            $table->dropColumn(['inventory_item_id', 'quantity']);
        });

        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_items');
    }
};
