<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Helper;
use App\Models\Supplier;
use App\Models\Residue;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\VehicleCheckin;
use App\Models\SupplierProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MobileController extends Controller
{
    /**
     * Show the login screen.
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->hasRole('driver')) {
            return redirect()->route('mobile.dashboard');
        }

        // Get all users who have the role 'driver'
        $drivers = User::role('driver')->orderBy('name')->get();

        return view('mobile.login', compact('drivers'));
    }

    /**
     * Handle authentication.
     */
    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$user->hasRole('driver')) {
            return back()->withErrors(['user_id' => 'O usuário selecionado não é um motorista cadastrado.']);
        }

        if (Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('mobile.dashboard');
        }

        return back()->withErrors(['password' => 'Senha incorreta. Verifique os dados e tente novamente.']);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mobile.login');
    }

    /**
     * Driver Dashboard.
     */
    public function dashboard()
    {
        $driver = Auth::user();

        // Get the active checkin (where checkout_date is null)
        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->with(['vehicle', 'helper', 'helper2'])
            ->first();

        // Calculate commissions
        $commissions = $this->calculateCommissions($driver->id);

        return view('mobile.dashboard', compact('activeCheckin', 'commissions'));
    }

    /**
     * Show Vehicle Check-in (Start trip).
     */
    public function showCheckin()
    {
        $driver = Auth::user();

        // Check if there is an active checkin
        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->first();

        if ($activeCheckin) {
            return redirect()->route('mobile.dashboard')->with('warning', 'Você já possui uma viagem ativa.');
        }

        $vehicles = Vehicle::where('status', 'Ativo')->orderBy('plate')->get();
        $helpers = Helper::where('is_active', true)->orderBy('name')->get();

        return view('mobile.checkin', compact('vehicles', 'helpers'));
    }

    /**
     * Store Vehicle Check-in.
     */
    public function checkin(Request $request)
    {
        $driver = Auth::user();

        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->first();

        if ($activeCheckin) {
            return redirect()->route('mobile.dashboard');
        }

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'helper_id' => 'nullable|exists:helpers,id',
            'helper_2_id' => 'nullable|exists:helpers,id|different:helper_id',
            'odometer_start' => 'required|integer|min:0',
            'num_drums' => 'required|integer|min:0',
            'check_tires' => 'nullable|boolean',
            'check_brakes' => 'nullable|boolean',
            'check_lights' => 'nullable|boolean',
            'check_oil' => 'nullable|boolean',
            'check_wipers' => 'nullable|boolean',
            'is_impeditivo' => 'nullable|boolean',
            'obs' => 'nullable|string',
        ]);

        VehicleCheckin::create([
            'vehicle_id' => $request->vehicle_id,
            'driver_user_id' => $driver->id,
            'helper_id' => $request->helper_id,
            'helper_2_id' => $request->helper_2_id,
            'odometer_start' => $request->odometer_start,
            'num_drums' => $request->num_drums,
            'check_tires' => $request->has('check_tires'),
            'check_brakes' => $request->has('check_brakes'),
            'check_lights' => $request->has('check_lights'),
            'check_oil' => $request->has('check_oil'),
            'check_wipers' => $request->has('check_wipers'),
            'is_impeditivo' => $request->has('is_impeditivo'),
            'obs' => $request->obs,
            'check_date' => now()->toDateString(),
        ]);

        if ($request->has('is_impeditivo')) {
            return redirect()->route('mobile.dashboard')->with('error', 'Check-in registrado com problema impeditivo. Veículo não liberado para viagem.');
        }

        return redirect()->route('mobile.dashboard')->with('success', 'Viagem iniciada com sucesso!');
    }

    /**
     * Show Vehicle Check-out (End trip).
     */
    public function showCheckout()
    {
        $driver = Auth::user();

        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->first();

        if (!$activeCheckin) {
            return redirect()->route('mobile.dashboard')->with('warning', 'Você não possui uma viagem ativa para encerrar.');
        }

        return view('mobile.checkout', compact('activeCheckin'));
    }

    /**
     * Store Vehicle Check-out.
     */
    public function checkout(Request $request)
    {
        $driver = Auth::user();

        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->first();

        if (!$activeCheckin) {
            return redirect()->route('mobile.dashboard');
        }

        $request->validate([
            'odometer_end' => 'required|integer|gte:' . $activeCheckin->odometer_start,
            'obs' => 'nullable|string',
        ], [
            'odometer_end.gte' => 'O odômetro final não pode ser menor do que o inicial (' . $activeCheckin->odometer_start . ' KM).',
        ]);

        $activeCheckin->update([
            'odometer_end' => $request->odometer_end,
            'checkout_date' => now(),
            'obs' => $activeCheckin->obs ? $activeCheckin->obs . "\nCheckout: " . $request->obs : $request->obs,
        ]);

        return redirect()->route('mobile.dashboard')->with('success', 'Viagem encerrada com sucesso!');
    }

    /**
     * AJAX endpoint to fetch vehicle last odometer.
     */
    public function getVehicleOdometer($id)
    {
        $lastCheckin = VehicleCheckin::where('vehicle_id', $id)
            ->whereNotNull('odometer_end')
            ->orderBy('checkout_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'odometer' => $lastCheckin ? $lastCheckin->odometer_end : 0
        ]);
    }

    /**
     * Show Register Collection form.
     */
    public function showCollection()
    {
        $driver = Auth::user();

        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->first();

        if (!$activeCheckin) {
            return redirect()->route('mobile.dashboard')->with('error', 'É necessário iniciar uma viagem (Check-in) antes de registrar coletas.');
        }

        if ($activeCheckin->is_impeditivo) {
            return redirect()->route('mobile.dashboard')->with('error', 'O veículo atual está com problema impeditivo e não pode realizar viagens/coletas.');
        }

        $suppliers = Supplier::orderBy('name')->get();
        $residues = Residue::where('is_active', true)->orderBy('name')->get();

        return view('mobile.collection', compact('suppliers', 'residues'));
    }

    /**
     * Store Register Collection.
     */
    public function collection(Request $request)
    {
        $driver = Auth::user();

        $activeCheckin = VehicleCheckin::where('driver_user_id', $driver->id)
            ->whereNull('checkout_date')
            ->first();

        if (!$activeCheckin || $activeCheckin->is_impeditivo) {
            return redirect()->route('mobile.dashboard')->with('error', 'Operação não permitida. Sem veículo ativo ou veículo com problema impeditivo.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.residue_id' => 'required|exists:residues,id',
            'items.*.weight' => 'required|numeric|min:0.01',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);
        
        // Setup initial Collection record
        $collection = new Collection([
            'supplier_id' => $supplier->id,
            'collection_date' => now(),
            'driver_user_id' => $driver->id,
            'driver_name' => $driver->name,
            'vehicle_id' => $activeCheckin->vehicle_id,
            'vehicle_plate' => $activeCheckin->vehicle->plate,
            'helper_id' => $activeCheckin->helper_id,
            'helper_2_id' => $activeCheckin->helper_2_id,
            'status' => 'Coletada',
        ]);

        $collection->save();

        $totalWeight = 0;
        $totalCost = 0;
        $residueNames = [];

        foreach ($request->items as $itemData) {
            $residueId = $itemData['residue_id'];
            $weight = (float)$itemData['weight'];

            // Determine price per kg for this residue & supplier
            $pricePerKg = SupplierProductPrice::where('supplier_id', $supplier->id)
                ->where('residue_id', $residueId)
                ->value('price_per_kg');

            if (is_null($pricePerKg)) {
                $pricePerKg = (float)$supplier->price_per_kg; // fallback to supplier base price_per_kg
            }

            $cost = round($weight * (float)$pricePerKg, 2);

            CollectionItem::create([
                'collection_id' => $collection->id,
                'residue_id' => $residueId,
                'weight' => $weight,
                'price_per_kg' => $pricePerKg,
                'total_cost' => $cost,
            ]);

            $totalWeight += $weight;
            $totalCost += $cost;

            $residue = Residue::find($residueId);
            if ($residue) {
                $residueNames[] = $residue->name;
            }
        }

        // Update main collection totals for search & performance fallback
        $collection->update([
            'weight' => $totalWeight,
            'total_cost' => $totalCost,
            'residue_type' => implode(', ', array_unique($residueNames)),
        ]);

        return redirect()->route('mobile.dashboard')->with('success', 'Coleta registrada com sucesso!');
    }

    /**
     * Detailed Commissions View.
     */
    public function commissions()
    {
        $driver = Auth::user();
        $commissions = $this->calculateCommissions($driver->id, true); // true for listing all items

        return view('mobile.commissions', compact('commissions'));
    }

    /**
     * Compute commissions logic.
     */
    private function calculateCommissions($driverId, $includeDetails = false)
    {
        $collections = Collection::where('driver_user_id', $driverId)
            ->where('status', 'Coletada')
            ->with(['supplier.route.commissionParameters', 'items.residue'])
            ->orderBy('collection_date', 'desc')
            ->get();

        $todayCommission = 0;
        $monthCommission = 0;
        $totalCommission = 0;

        $todayStr = now()->toDateString();
        $monthStr = now()->format('Y-m');

        $details = [];

        foreach ($collections as $collection) {
            $route = $collection->supplier?->route;
            if (!$route) {
                continue;
            }

            $params = $route->commissionParameters->pluck('commission_per_kg_driver', 'residue_id');
            $colCommission = 0;
            $itemDetails = [];

            foreach ($collection->items as $item) {
                $commPerKg = (float)($params->get($item->residue_id) ?? 0.0000);
                $itemCommission = (float)$item->weight * $commPerKg;
                $colCommission += $itemCommission;

                if ($includeDetails) {
                    $itemDetails[] = [
                        'residue' => $item->residue?->name ?? 'Resíduo',
                        'weight' => $item->weight,
                        'comm_per_kg' => $commPerKg,
                        'total' => $itemCommission,
                    ];
                }
            }

            $colDate = $collection->collection_date;
            $dateStr = $colDate ? $colDate->toDateString() : null;
            $colMonthStr = $colDate ? $colDate->format('Y-m') : null;

            if ($dateStr === $todayStr) {
                $todayCommission += $colCommission;
            }
            if ($colMonthStr === $monthStr) {
                $monthCommission += $colCommission;
            }
            $totalCommission += $colCommission;

            if ($includeDetails && $colCommission > 0) {
                $details[] = [
                    'date' => $colDate ? $colDate->format('d/m/Y H:i') : '-',
                    'supplier' => $collection->supplier?->name ?? 'Fornecedor',
                    'total_commission' => $colCommission,
                    'items' => $itemDetails,
                ];
            }
        }

        return [
            'today' => $todayCommission,
            'month' => $monthCommission,
            'total' => $totalCommission,
            'details' => $details,
        ];
    }
}
