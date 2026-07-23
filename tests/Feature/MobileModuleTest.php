<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Helper;
use App\Models\Supplier;
use App\Models\Residue;
use App\Models\Route;
use App\Models\RouteCommissionParameter;
use App\Models\SupplierProductPrice;
use App\Models\VehicleCheckin;
use App\Models\Collection;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $driverRole;
    protected $driverUser;
    protected $normalUser;
    protected $vehicle;
    protected $helper;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Roles
        $this->driverRole = Role::firstOrCreate(['name' => 'driver', 'guard_name' => 'web']);

        // 2. Setup Users
        $this->driverUser = User::factory()->create([
            'name' => 'Carlos Motorista',
            'email' => 'carlos@motorista.com',
            'password' => bcrypt('senha123'),
        ]);
        $this->driverUser->assignRole($this->driverRole);

        $this->normalUser = User::factory()->create([
            'name' => 'User Administrativo',
            'email' => 'admin@admin.com',
            'password' => bcrypt('senha123'),
        ]);

        // 3. Setup Vehicle and Helper
        $this->vehicle = Vehicle::create([
            'plate' => 'KGS-2026',
            'brand_model' => 'VW Constellation',
            'color' => 'Branco',
            'year_fabrication' => 2020,
            'year_model' => 2021,
            'status' => 'Ativo',
        ]);

        $this->helper = Helper::create([
            'name' => 'Manoel Ajudante',
            'is_active' => true,
        ]);
    }

    /**
     * Test mobile login restrict to driver role.
     */
    public function test_only_driver_role_can_login_to_mobile(): void
    {
        // Get login page
        $response = $this->get(route('mobile.login'));
        $response->assertStatus(200);

        // Try login with normal user -> should fail with validation error
        $response = $this->post(route('mobile.login.post'), [
            'user_id' => $this->normalUser->id,
            'password' => 'senha123',
        ]);
        $response->assertSessionHasErrors('user_id');
        $this->assertFalse(auth()->check());

        // Try login with driver but wrong password -> should fail
        $response = $this->post(route('mobile.login.post'), [
            'user_id' => $this->driverUser->id,
            'password' => 'errada',
        ]);
        $response->assertSessionHasErrors('password');
        $this->assertFalse(auth()->check());

        // Try login with driver and correct password -> should succeed and redirect to dashboard
        $response = $this->post(route('mobile.login.post'), [
            'user_id' => $this->driverUser->id,
            'password' => 'senha123',
        ]);
        $response->assertRedirect(route('mobile.dashboard'));
        $this->assertTrue(auth()->check());
        $this->assertEquals($this->driverUser->id, auth()->id());
    }

    /**
     * Test guest cannot access dashboard.
     */
    public function test_guest_and_non_drivers_cannot_access_mobile_dashboard(): void
    {
        // Guest -> should redirect to mobile.login
        $response = $this->get(route('mobile.dashboard'));
        $response->assertRedirect(route('mobile.login'));

        // Login as normal user -> should abort with 403
        $this->actingAs($this->normalUser);
        $response = $this->get(route('mobile.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test full trip workflow (Checkin -> Checkout).
     */
    public function test_driver_can_perform_checkin_and_checkout(): void
    {
        $this->actingAs($this->driverUser);

        // Checkin page
        $response = $this->get(route('mobile.checkin'));
        $response->assertStatus(200);

        // Submit check-in
        $response = $this->post(route('mobile.checkin.post'), [
            'vehicle_id' => $this->vehicle->id,
            'helper_id' => $this->helper->id,
            'odometer_start' => 50000,
            'num_drums' => 15,
            'check_tires' => '1',
            'check_brakes' => '1',
            'check_lights' => '1',
            'check_oil' => '1',
            'check_wipers' => '1',
        ]);

        $response->assertRedirect(route('mobile.dashboard'));
        $this->assertDatabaseHas('vehicle_checkins', [
            'driver_user_id' => $this->driverUser->id,
            'vehicle_id' => $this->vehicle->id,
            'odometer_start' => 50000,
            'num_drums' => 15,
            'checkout_date' => null,
        ]);

        // Attempting to checkin again while trip is active should redirect back
        $response = $this->get(route('mobile.checkin'));
        $response->assertRedirect(route('mobile.dashboard'));

        // Checkout page
        $response = $this->get(route('mobile.checkout'));
        $response->assertStatus(200);

        // Submit check-out with smaller odometer should fail validation
        $response = $this->post(route('mobile.checkout.post'), [
            'odometer_end' => 49999,
        ]);
        $response->assertSessionHasErrors('odometer_end');

        // Submit valid check-out
        $response = $this->post(route('mobile.checkout.post'), [
            'odometer_end' => 50250,
            'obs' => 'Tudo certo',
        ]);

        $response->assertRedirect(route('mobile.dashboard'));
        $this->assertDatabaseHas('vehicle_checkins', [
            'driver_user_id' => $this->driverUser->id,
            'odometer_end' => 50250,
        ]);
        $this->assertNotNull(VehicleCheckin::where('driver_user_id', $this->driverUser->id)->first()->checkout_date);
    }

    /**
     * Test last odometer API endpoint.
     */
    public function test_vehicle_odometer_endpoint_returns_correct_value(): void
    {
        $this->actingAs($this->driverUser);

        // Create completed checkin for this vehicle
        VehicleCheckin::create([
            'vehicle_id' => $this->vehicle->id,
            'driver_user_id' => $this->driverUser->id,
            'odometer_start' => 50000,
            'odometer_end' => 50320,
            'check_date' => now(),
            'checkout_date' => now(),
        ]);

        $response = $this->get(route('mobile.vehicle.odometer', ['id' => $this->vehicle->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'odometer' => 50320,
        ]);
    }

    /**
     * Test registration of collections inside active checkin session.
     */
    public function test_driver_can_register_collection_inheriting_checkin_data(): void
    {
        $this->actingAs($this->driverUser);

        // Create route and supplier
        $route = Route::create(['name' => 'Rota Sul', 'is_active' => true]);
        
        $supplier = Supplier::create([
            'name' => 'Talho da Esquina',
            'document' => '111222',
            'type' => 'Açougue',
            'price_per_kg' => 0.50, // Base price
            'route_id' => $route->id,
        ]);

        $residue = Residue::create(['name' => 'Ossada', 'is_active' => true]);

        // Start active check-in
        $checkin = VehicleCheckin::create([
            'vehicle_id' => $this->vehicle->id,
            'driver_user_id' => $this->driverUser->id,
            'helper_id' => $this->helper->id,
            'odometer_start' => 120000,
            'check_date' => now(),
        ]);

        // Register collection
        $response = $this->post(route('mobile.collection.post'), [
            'supplier_id' => $supplier->id,
            'items' => [
                [
                    'residue_id' => $residue->id,
                    'weight' => 200.00,
                ]
            ],
        ]);

        $response->assertRedirect(route('mobile.dashboard'));

        // Verify collection was recorded inheriting driver, vehicle and helpers from active checkin
        $this->assertDatabaseHas('collections', [
            'supplier_id' => $supplier->id,
            'driver_user_id' => $this->driverUser->id,
            'vehicle_id' => $this->vehicle->id,
            'helper_id' => $this->helper->id,
            'status' => 'Coletada',
            'weight' => 200.00,
            'total_cost' => 100.00, // 200 * 0.50
        ]);

        $collection = Collection::where('supplier_id', $supplier->id)->first();
        
        $this->assertDatabaseHas('collection_items', [
            'collection_id' => $collection->id,
            'residue_id' => $residue->id,
            'weight' => 200.00,
            'price_per_kg' => 0.50,
            'total_cost' => 100.00,
        ]);
    }

    /**
     * Test dynamic commission calculation.
     */
    public function test_driver_can_see_calculated_commissions(): void
    {
        $this->actingAs($this->driverUser);

        // 1. Setup route, supplier, residue
        $route = Route::create(['name' => 'Rota Leste', 'is_active' => true]);
        
        $supplier = Supplier::create([
            'name' => 'Açougue Grande',
            'document' => '999888',
            'type' => 'Açougue',
            'price_per_kg' => 0.40,
            'route_id' => $route->id,
        ]);

        $residue = Residue::create(['name' => 'Gordura Pura', 'is_active' => true]);

        // 2. Setup commission parameters
        RouteCommissionParameter::create([
            'route_id' => $route->id,
            'residue_id' => $residue->id,
            'commission_per_kg_driver' => 0.1200, // 12 cents per kg
            'commission_per_kg_helper' => 0.0500,
        ]);

        // 3. Create collection
        $collection = Collection::create([
            'supplier_id' => $supplier->id,
            'collection_date' => now(),
            'driver_user_id' => $this->driverUser->id,
            'driver_name' => $this->driverUser->name,
            'vehicle_id' => $this->vehicle->id,
            'status' => 'Coletada',
            'weight' => 150.00,
            'total_cost' => 60.00,
        ]);

        \App\Models\CollectionItem::create([
            'collection_id' => $collection->id,
            'residue_id' => $residue->id,
            'weight' => 150.00,
            'price_per_kg' => 0.40,
            'total_cost' => 60.00,
        ]);

        // Verify commissions page displays correct values
        $response = $this->get(route('mobile.commissions'));
        $response->assertStatus(200);

        // Commission expected: 150 kg * 0.12 = R$ 18.00
        $response->assertSee('R$ 18,00');
    }
}
