<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\CostCategory;
use App\Models\OperationalCost;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Client;
use App\Models\Sale;
use App\Models\TallowQualityCertificate;
use App\Models\MealQualityCertificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase4Test extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_transactions_reactive_stock_adjustments(): void
    {
        $item = InventoryItem::create([
            'name' => 'Filtro de Óleo PSD',
            'sku' => 'FO-1234',
            'stock' => 10.00,
            'min_stock' => 2.00,
            'unit' => 'UN',
        ]);

        $this->assertEquals(10.00, $item->stock);

        // Test Entrada
        InventoryTransaction::create([
            'inventory_item_id' => $item->id,
            'type' => 'Entrada',
            'quantity' => 5.00,
            'description' => 'Compra de peças extras',
            'transaction_date' => now(),
        ]);

        $item->refresh();
        $this->assertEquals(15.00, $item->stock);

        // Test Saída
        InventoryTransaction::create([
            'inventory_item_id' => $item->id,
            'type' => 'Saída',
            'quantity' => 3.00,
            'description' => 'Uso na revisão do motor',
            'transaction_date' => now(),
        ]);

        $item->refresh();
        $this->assertEquals(12.00, $item->stock);
    }

    public function test_operational_cost_auto_deducts_inventory(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'ABC-9999',
            'brand_model' => 'Ford Cargo',
            'color' => 'Branco',
            'year_fabrication' => 2015,
            'year_model' => 2016,
        ]);

        $item = InventoryItem::create([
            'name' => 'Pastilha de Freio Axor',
            'sku' => 'PF-900',
            'stock' => 8.00,
            'min_stock' => 2.00,
        ]);

        $category = CostCategory::firstOrCreate(['name' => 'Peças']);

        $cost = OperationalCost::create([
            'vehicle_id' => $vehicle->id,
            'cost_category_id' => $category->id,
            'description' => 'Troca de pastilhas de freio',
            'value' => 600.00,
            'cost_date' => now()->toDateString(),
            'inventory_item_id' => $item->id,
            'quantity' => 2.00,
        ]);

        // Stock should be decremented automatically from 8 to 6
        $item->refresh();
        $this->assertEquals(6.00, $item->stock);

        // Assert that a Saída transaction was automatically recorded
        $this->assertDatabaseHas('inventory_transactions', [
            'inventory_item_id' => $item->id,
            'type' => 'Saída',
            'quantity' => 2.00,
        ]);
    }

    public function test_quality_certificates_creation_and_download(): void
    {
        $client = Client::create([
            'name' => 'Fábrica de Ração ABC',
            'document' => '11222333000199',
        ]);

        $sale = Sale::create([
            'client_id' => $client->id,
            'product_type' => 'Sebo',
            'weight' => 15000.00,
            'price_per_kg' => 3.50,
            'total_value' => 52500.00,
            'sale_date' => now(),
            'status' => 'Pendente',
        ]);

        $cert = TallowQualityCertificate::create([
            'sale_id' => $sale->id,
            'client_id' => $client->id,
            'analysis_date' => now()->toDateString(),
            'shipping_date' => now()->toDateString(),
            'production_date' => '07/2026',
            'expiry_info' => '120 dias',
            'result_aspect' => 'Límpido',
            'result_acidity' => '1.5%',
            'result_impurities' => '0.8%',
            'result_odor' => 'Característico',
            'result_moisture' => '0.4%',
            'vehicle_plate' => 'ABC-1234',
            'carrier_name' => 'Transportes X',
            'invoice_number' => 'NF-1000',
            'seal_number' => 'L-9988',
            'qa_responsible' => 'Laboratório 1',
            'technical_responsible' => 'Garantia 1',
        ]);

        $this->assertDatabaseHas('tallow_quality_certificates', [
            'sale_id' => $sale->id,
            'invoice_number' => 'NF-1000',
        ]);

        // Test the PDF generation endpoint
        $response = $this->get(route('sales.certificate.pdf', ['sale' => $sale->id]));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
