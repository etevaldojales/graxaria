<?php

namespace Tests\Feature;

use App\Models\Route;
use App\Models\RouteCommissionParameter;
use App\Models\Residue;
use App\Models\Supplier;
use App\Models\GatehouseWeighing;
use App\Models\CostCategory;
use App\Models\OperationalCost;
use App\Models\FuelSupply;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Collection;
use App\Models\CollectionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_route_and_commission_parameters(): void
    {
        $route = Route::create([
            'name' => 'Rota Leste',
            'is_active' => true,
        ]);

        $residue = Residue::create([
            'name' => 'Pele',
            'is_active' => true,
        ]);

        $param = RouteCommissionParameter::create([
            'route_id' => $route->id,
            'residue_id' => $residue->id,
            'commission_per_kg_driver' => 0.1500,
            'commission_per_kg_helper' => 0.0800,
        ]);

        $this->assertDatabaseHas('routes', [
            'name' => 'Rota Leste',
        ]);

        $this->assertDatabaseHas('route_commission_parameters', [
            'route_id' => $route->id,
            'residue_id' => $residue->id,
            'commission_per_kg_driver' => 0.1500,
            'commission_per_kg_helper' => 0.0800,
        ]);
    }

    public function test_supplier_belongs_to_route(): void
    {
        $route = Route::create(['name' => 'Rota Norte']);
        $supplier = Supplier::create([
            'name' => 'Açougue Central',
            'document' => '12345678',
            'type' => 'Açougue',
            'price_per_kg' => 1.20,
            'route_id' => $route->id,
        ]);

        $this->assertEquals('Rota Norte', $supplier->route->name);
        $this->assertCount(1, $route->suppliers);
    }

    public function test_gatehouse_weighing_calculations(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'ABC-9999',
            'brand_model' => 'Ford Cargo',
            'color' => 'Branco',
            'year_fabrication' => 2015,
            'year_model' => 2016,
        ]);

        $driver = User::factory()->create();

        $weighing = GatehouseWeighing::create([
            'vehicle_id' => $vehicle->id,
            'driver_user_id' => $driver->id,
            'gross_weight' => 15000.00,
            'tare_weight' => 8000.00,
            'net_weight' => 7000.00,
            'trip_number' => 1,
            'weighing_date' => now(),
            'status' => 'Concluído',
        ]);

        $this->assertDatabaseHas('gatehouse_weighings', [
            'id' => $weighing->id,
            'gross_weight' => 15000.00,
            'tare_weight' => 8000.00,
            'net_weight' => 7000.00,
        ]);
    }

    public function test_operational_costs(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'ABC-9999',
            'brand_model' => 'Ford Cargo',
            'color' => 'Branco',
            'year_fabrication' => 2015,
            'year_model' => 2016,
        ]);

        $category = CostCategory::firstOrCreate(['name' => 'Pneus']);

        $cost = OperationalCost::create([
            'vehicle_id' => $vehicle->id,
            'cost_category_id' => $category->id,
            'description' => 'Troca de pneus traseiros',
            'value' => 2400.00,
            'invoice_number' => 'NF-123',
            'cost_date' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('operational_costs', [
            'vehicle_id' => $vehicle->id,
            'cost_category_id' => $category->id,
            'value' => 2400.00,
        ]);
    }

    public function test_fuel_supply_and_average_calculations(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'ABC-9999',
            'brand_model' => 'Ford Cargo',
            'color' => 'Branco',
            'year_fabrication' => 2015,
            'year_model' => 2016,
        ]);

        $driver = User::factory()->create();

        // First supply
        $supply1 = FuelSupply::create([
            'vehicle_id' => $vehicle->id,
            'driver_user_id' => $driver->id,
            'liters' => 50.00,
            'price_per_liter' => 6.0000,
            'total_value' => 300.00,
            'odometer' => 1000,
            'supply_date' => now()->subDays(2)->toDateString(),
        ]);

        // Second supply
        $supply2 = FuelSupply::create([
            'vehicle_id' => $vehicle->id,
            'driver_user_id' => $driver->id,
            'liters' => 50.00,
            'price_per_liter' => 6.0000,
            'total_value' => 300.00,
            'odometer' => 1500, // 500 KM run
            'supply_date' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('fuel_supplies', [
            'vehicle_id' => $vehicle->id,
            'odometer' => 1500,
        ]);

        // Verify the math
        $prevSupply = FuelSupply::where('vehicle_id', $supply2->vehicle_id)
            ->where('odometer', '<', $supply2->odometer)
            ->orderBy('odometer', 'desc')
            ->first();

        $this->assertNotNull($prevSupply);
        $this->assertEquals(1000, $prevSupply->odometer);
        
        $distance = $supply2->odometer - $prevSupply->odometer;
        $this->assertEquals(500, $distance);
        
        $kml = $distance / $supply2->liters;
        $this->assertEquals(10.00, $kml);
    }
}
