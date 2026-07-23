<?php

namespace Tests\Feature;

use App\Models\Helper;
use App\Models\Vehicle;
use App\Models\VehicleCheckin;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase2Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_vehicle_and_helper(): void
    {
        $driver = User::factory()->create(['name' => 'John Driver']);

        $vehicle = Vehicle::create([
            'plate' => 'ABC-1234',
            'brand_model' => 'Volvo VM 270',
            'color' => 'Prata',
            'year_fabrication' => 2019,
            'year_model' => 2020,
            'is_outsourced' => false,
            'driver_user_id' => $driver->id,
        ]);

        $helper = Helper::create([
            'name' => 'Bob Helper',
            'phone' => '11999998888',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('vehicles', [
            'plate' => 'ABC-1234',
            'driver_user_id' => $driver->id,
        ]);

        $this->assertDatabaseHas('helpers', [
            'name' => 'Bob Helper',
            'is_active' => true,
        ]);

        $this->assertEquals('John Driver', $vehicle->driver->name);
    }

    public function test_can_create_vehicle_checkin(): void
    {
        $driver = User::factory()->create();
        $vehicle = Vehicle::create([
            'plate' => 'XYZ-5678',
            'brand_model' => 'Scania R440',
            'color' => 'Vermelho',
            'year_fabrication' => 2018,
            'year_model' => 2018,
            'is_outsourced' => false,
        ]);
        $helper = Helper::create(['name' => 'Alice Helper']);

        $checkin = VehicleCheckin::create([
            'vehicle_id' => $vehicle->id,
            'driver_user_id' => $driver->id,
            'helper_id' => $helper->id,
            'odometer_start' => 150000,
            'check_tires' => true,
            'check_brakes' => true,
            'check_lights' => true,
            'check_oil' => true,
            'check_wipers' => true,
            'num_drums' => 20,
            'is_impeditivo' => false,
            'check_date' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('vehicle_checkins', [
            'vehicle_id' => $vehicle->id,
            'driver_user_id' => $driver->id,
            'odometer_start' => 150000,
            'num_drums' => 20,
        ]);

        // Test checkout
        $checkin->update([
            'odometer_end' => 150250,
            'checkout_date' => now(),
        ]);

        $this->assertDatabaseHas('vehicle_checkins', [
            'id' => $checkin->id,
            'odometer_end' => 150250,
        ]);
    }

    public function test_collection_relations(): void
    {
        $driver = User::factory()->create(['name' => 'Jose Driver']);
        $vehicle = Vehicle::create([
            'plate' => 'MNO-9012',
            'brand_model' => 'Mercedes Axor',
            'color' => 'Azul',
            'year_fabrication' => 2021,
            'year_model' => 2022,
        ]);
        $helper = Helper::create(['name' => 'Manoel Helper']);
        $supplier = Supplier::create([
            'name' => 'Açougue Central',
            'document' => '12.345.678/0001-99',
            'type' => 'Açougue',
        ]);

        $collection = Collection::create([
            'supplier_id' => $supplier->id,
            'collection_date' => now(),
            'driver_user_id' => $driver->id,
            'driver_name' => $driver->name,
            'vehicle_id' => $vehicle->id,
            'vehicle_plate' => $vehicle->plate,
            'helper_id' => $helper->id,
            'status' => 'Agendada',
        ]);

        $this->assertDatabaseHas('collections', [
            'id' => $collection->id,
            'driver_user_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'helper_id' => $helper->id,
            'driver_name' => 'Jose Driver',
            'vehicle_plate' => 'MNO-9012',
        ]);

        $this->assertEquals('Jose Driver', $collection->driver->name);
        $this->assertEquals('MNO-9012', $collection->vehicle->plate);
        $this->assertEquals('Manoel Helper', $collection->helper->name);
    }

    public function test_driver_role_filtering(): void
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'driver', 'guard_name' => 'web']);
        
        $driverUser = User::factory()->create(['name' => 'Real Driver']);
        $driverUser->assignRole($role);

        $normalUser = User::factory()->create(['name' => 'Normal User']);

        // Query filtered by role 'driver' should only return the driver user
        $drivers = User::role('driver')->get();
        $this->assertCount(1, $drivers);
        $this->assertEquals('Real Driver', $drivers->first()->name);
    }
}
