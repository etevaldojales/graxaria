<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create routes table
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create route_commission_parameters table
        Schema::create('route_commission_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->foreignId('residue_id')->constrained('residues')->cascadeOnDelete();
            $table->decimal('commission_per_kg_driver', 8, 4)->default(0.0000);
            $table->decimal('commission_per_kg_helper', 8, 4)->default(0.0000);
            $table->timestamps();

            $table->unique(['route_id', 'residue_id'], 'route_residue_unique');
        });

        // 3. Add route_id to suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('route_id')->nullable()->constrained('routes')->nullOnDelete();
        });

        // 4. Create gatehouse_weighings table
        Schema::create('gatehouse_weighings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('gross_weight', 10, 2);
            $table->decimal('tare_weight', 10, 2)->nullable();
            $table->decimal('net_weight', 10, 2)->nullable();
            $table->integer('trip_number')->default(1);
            $table->dateTime('weighing_date');
            $table->string('status')->default('Pendente_Tara'); // Pendente_Tara, Concluído, Cancelado
            $table->timestamps();
        });

        // 5. Create cost_categories table
        Schema::create('cost_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default cost categories
        $now = now();
        DB::table('cost_categories')->insert([
            ['name' => 'Manutenção', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pneus', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lubrificantes', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Combustível', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Peças', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Outros', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 6. Create operational_costs table
        Schema::create('operational_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cost_category_id')->constrained('cost_categories')->cascadeOnDelete();
            $table->string('description');
            $table->decimal('value', 10, 2);
            $table->string('invoice_number')->nullable();
            $table->date('cost_date');
            $table->timestamps();
        });

        // 7. Create fuel_supplies table
        Schema::create('fuel_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('liters', 8, 2);
            $table->decimal('price_per_liter', 8, 4);
            $table->decimal('total_value', 10, 2);
            $table->integer('odometer');
            $table->string('coupon_number')->nullable();
            $table->string('fuel_type')->default('Diesel S10'); // Diesel S10, Diesel Comum, Outros
            $table->date('supply_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_supplies');
        Schema::dropIfExists('operational_costs');
        Schema::dropIfExists('cost_categories');
        Schema::dropIfExists('gatehouse_weighings');

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');
        });

        Schema::dropIfExists('route_commission_parameters');
        Schema::dropIfExists('routes');
    }
};
