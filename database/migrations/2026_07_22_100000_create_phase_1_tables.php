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
        // 1. Create residues table
        Schema::create('residues', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default residues
        $now = now();
        DB::table('residues')->insert([
            ['name' => 'Ossos', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gordura', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Miúdos', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Misto', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 2. Create supplier_product_prices table
        Schema::create('supplier_product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('residue_id')->constrained()->onDelete('cascade');
            $table->decimal('price_per_kg', 8, 2);
            $table->timestamps();

            $table->unique(['supplier_id', 'residue_id']);
        });

        // Populate default supplier prices based on the existing suppliers' price_per_kg
        $suppliers = DB::table('suppliers')->get();
        $residues = DB::table('residues')->get();
        foreach ($suppliers as $supplier) {
            foreach ($residues as $residue) {
                DB::table('supplier_product_prices')->insert([
                    'supplier_id' => $supplier->id,
                    'residue_id' => $residue->id,
                    'price_per_kg' => $supplier->price_per_kg,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 3. Create collection_items table
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->foreignId('residue_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 10, 2);
            $table->decimal('price_per_kg', 8, 2);
            $table->decimal('total_cost', 10, 2);
            $table->timestamps();
        });

        // 4. Migrate existing collection data to collection_items
        $collections = DB::table('collections')->get();
        foreach ($collections as $col) {
            $residueName = $col->residue_type ?? 'Misto';
            $residue = DB::table('residues')->where('name', $residueName)->first();
            if (!$residue) {
                $residue = DB::table('residues')->where('name', 'Misto')->first();
            }

            DB::table('collection_items')->insert([
                'collection_id' => $col->id,
                'residue_id' => $residue->id,
                'weight' => $col->weight ?? 0.00,
                'price_per_kg' => $col->price_per_kg ?? 0.00,
                'total_cost' => $col->total_cost ?? 0.00,
                'created_at' => $col->created_at,
                'updated_at' => $col->updated_at,
            ]);
        }

        // 5. Make legacy collection columns nullable
        Schema::table('collections', function (Blueprint $table) {
            $table->string('residue_type')->nullable()->change();
            $table->decimal('weight', 10, 2)->nullable()->change();
            $table->decimal('price_per_kg', 8, 2)->nullable()->change();
            $table->decimal('total_cost', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert columns to not nullable
        Schema::table('collections', function (Blueprint $table) {
            $table->string('residue_type')->nullable(false)->change();
            $table->decimal('weight', 10, 2)->nullable(false)->change();
            $table->decimal('price_per_kg', 8, 2)->nullable(false)->change();
            $table->decimal('total_cost', 10, 2)->nullable(false)->change();
        });

        Schema::dropIfExists('collection_items');
        Schema::dropIfExists('supplier_product_prices');
        Schema::dropIfExists('residues');
    }
};
