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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->dateTime('collection_date');
            $table->string('residue_type')->default('Misto'); // Ossos, Gordura, Miúdos, Misto
            $table->decimal('weight', 10, 2); // weight in kg
            $table->decimal('price_per_kg', 8, 2)->default(0.00);
            $table->decimal('total_cost', 10, 2)->default(0.00); // weight * price_per_kg
            $table->string('driver_name')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->string('status')->default('Agendada'); // Agendada, Coletada, Cancelada
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
