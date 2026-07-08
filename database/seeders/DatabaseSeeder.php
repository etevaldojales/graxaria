<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::create([
            'name' => 'Administrador SisGraxaria',
            'email' => 'admin@sisgraxaria.com',
            'password' => bcrypt('admin123'),
        ]);

        // 2. Create Suppliers
        $sup1 = \App\Models\Supplier::create([
            'name' => 'Frigorífico Boi Nobre',
            'document' => '12.345.678/0001-99',
            'type' => 'Frigorífico',
            'phone' => '(85) 98888-1111',
            'email' => 'contato@boinobre.com',
            'address' => 'Rodovia BR 116, Km 15, Messejana, Fortaleza - CE',
            'price_per_kg' => 0.45,
        ]);

        $sup2 = \App\Models\Supplier::create([
            'name' => 'Açougue do Zé Cabra',
            'document' => '123.456.789-00',
            'type' => 'Açougue',
            'phone' => '(85) 99999-2222',
            'email' => 'jose@cabra.com',
            'address' => 'Rua Floriano Peixoto, 1050, Centro, Fortaleza - CE',
            'price_per_kg' => 0.40,
        ]);

        $sup3 = \App\Models\Supplier::create([
            'name' => 'Supermercado Pague Menos',
            'document' => '98.765.432/0001-11',
            'type' => 'Supermercado',
            'phone' => '(85) 3400-3333',
            'email' => 'recebimento@paguemenos.com',
            'address' => 'Av. Santos Dumont, 2500, Aldeota, Fortaleza - CE',
            'price_per_kg' => 0.35,
        ]);

        $sup4 = \App\Models\Supplier::create([
            'name' => 'Casa de Carnes Prime',
            'document' => '45.678.901/0001-22',
            'type' => 'Açougue',
            'phone' => '(85) 98765-4444',
            'email' => 'prime@carnes.com',
            'address' => 'Av. Washington Soares, 3000, Edson Queiroz, Fortaleza - CE',
            'price_per_kg' => 0.50,
        ]);

        // 3. Create Clients
        $cli1 = \App\Models\Client::create([
            'name' => 'Sabões Minuano S/A',
            'document' => '11.222.333/0001-44',
            'company_name' => 'Minuano Indústria Química Ltda',
            'phone' => '(11) 4004-9999',
            'email' => 'compras@minuano.com.br',
            'address' => 'Distrito Industrial, Simões Filho - BA',
        ]);

        $cli2 = \App\Models\Client::create([
            'name' => 'Rações Primor Nordeste',
            'document' => '55.666.777/0001-88',
            'company_name' => 'Primor Nutrição Animal S/A',
            'phone' => '(81) 3200-5555',
            'email' => 'suprimentos@racoesprimor.com.br',
            'address' => 'Av. Industrial, Cabo de Santo Agostinho - PE',
        ]);

        // 4. Create Batches (Lotes)
        $batch1 = \App\Models\Batch::create([
            'batch_code' => 'LOT-20260705-01',
            'start_date' => '2026-07-05 08:00:00',
            'end_date' => '2026-07-05 16:30:00',
            'input_weight' => 5200.00,
            'output_tallow_weight' => 1820.00, // ~35% yield
            'output_meal_weight' => 1560.00,   // ~30% yield
            'yield_percentage' => 65.00,       // total 65% yield
            'status' => 'Concluído',
        ]);

        $batch2 = \App\Models\Batch::create([
            'batch_code' => 'LOT-20260707-01',
            'start_date' => '2026-07-07 07:00:00',
            'end_date' => null,
            'input_weight' => 3100.00,
            'output_tallow_weight' => 0.00,
            'output_meal_weight' => 0.00,
            'yield_percentage' => 0.00,
            'status' => 'Em Processamento',
        ]);

        // 5. Create Collections (Coletas)
        // Associated to Batch 1 (Processed)
        \App\Models\Collection::create([
            'supplier_id' => $sup1->id,
            'collection_date' => '2026-07-05 09:00:00',
            'residue_type' => 'Ossos',
            'weight' => 2500.00,
            'price_per_kg' => 0.45,
            'total_cost' => 1125.00,
            'driver_name' => 'Antônio Silva',
            'vehicle_plate' => 'OSX-5E20',
            'status' => 'Coletada',
            'batch_id' => $batch1->id,
        ]);

        \App\Models\Collection::create([
            'supplier_id' => $sup2->id,
            'collection_date' => '2026-07-05 10:15:00',
            'residue_type' => 'Gordura',
            'weight' => 1200.00,
            'price_per_kg' => 0.40,
            'total_cost' => 480.00,
            'driver_name' => 'Carlos Santos',
            'vehicle_plate' => 'ABC-1234',
            'status' => 'Coletada',
            'batch_id' => $batch1->id,
        ]);

        \App\Models\Collection::create([
            'supplier_id' => $sup3->id,
            'collection_date' => '2026-07-05 11:30:00',
            'residue_type' => 'Misto',
            'weight' => 1500.00,
            'price_per_kg' => 0.35,
            'total_cost' => 525.00,
            'driver_name' => 'Antônio Silva',
            'vehicle_plate' => 'OSX-5E20',
            'status' => 'Coletada',
            'batch_id' => $batch1->id,
        ]);

        // Associated to Batch 2 (Processing)
        \App\Models\Collection::create([
            'supplier_id' => $sup1->id,
            'collection_date' => '2026-07-07 08:30:00',
            'residue_type' => 'Misto',
            'weight' => 2000.00,
            'price_per_kg' => 0.45,
            'total_cost' => 900.00,
            'driver_name' => 'Carlos Santos',
            'vehicle_plate' => 'ABC-1234',
            'status' => 'Coletada',
            'batch_id' => $batch2->id,
        ]);

        \App\Models\Collection::create([
            'supplier_id' => $sup4->id,
            'collection_date' => '2026-07-07 09:45:00',
            'residue_type' => 'Gordura',
            'weight' => 1100.00,
            'price_per_kg' => 0.50,
            'total_cost' => 550.00,
            'driver_name' => 'Antônio Silva',
            'vehicle_plate' => 'OSX-5E20',
            'status' => 'Coletada',
            'batch_id' => $batch2->id,
        ]);

        // Scheduled / Canceled
        \App\Models\Collection::create([
            'supplier_id' => $sup2->id,
            'collection_date' => '2026-07-08 09:00:00',
            'residue_type' => 'Ossos',
            'weight' => 1000.00,
            'price_per_kg' => 0.40,
            'total_cost' => 400.00,
            'driver_name' => 'Carlos Santos',
            'vehicle_plate' => 'ABC-1234',
            'status' => 'Agendada',
            'batch_id' => null,
        ]);

        \App\Models\Collection::create([
            'supplier_id' => $sup3->id,
            'collection_date' => '2026-07-08 11:00:00',
            'residue_type' => 'Misto',
            'weight' => 1300.00,
            'price_per_kg' => 0.35,
            'total_cost' => 455.00,
            'driver_name' => 'Antônio Silva',
            'vehicle_plate' => 'OSX-5E20',
            'status' => 'Agendada',
            'batch_id' => null,
        ]);

        \App\Models\Collection::create([
            'supplier_id' => $sup4->id,
            'collection_date' => '2026-07-06 14:00:00',
            'residue_type' => 'Miúdos',
            'weight' => 800.00,
            'price_per_kg' => 0.50,
            'total_cost' => 400.00,
            'driver_name' => 'Carlos Santos',
            'vehicle_plate' => 'ABC-1234',
            'status' => 'Cancelada',
            'batch_id' => null,
        ]);

        // 6. Create Sales (Vendas)
        \App\Models\Sale::create([
            'client_id' => $cli1->id,
            'product_type' => 'Sebo',
            'weight' => 1800.00,
            'price_per_kg' => 4.50,
            'total_value' => 8100.00,
            'sale_date' => '2026-07-06 10:00:00',
            'status' => 'Pago',
        ]);

        \App\Models\Sale::create([
            'client_id' => $cli2->id,
            'product_type' => 'Farinha',
            'weight' => 1500.00,
            'price_per_kg' => 2.20,
            'total_value' => 3300.00,
            'sale_date' => '2026-07-06 11:30:00',
            'status' => 'Pago',
        ]);

        \App\Models\Sale::create([
            'client_id' => $cli1->id,
            'product_type' => 'Sebo',
            'weight' => 2000.00,
            'price_per_kg' => 4.60,
            'total_value' => 9200.00,
            'sale_date' => '2026-07-07 14:00:00',
            'status' => 'Pendente',
        ]);
    }
}
