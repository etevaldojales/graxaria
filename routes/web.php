<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

// Rota auxiliar de deploy seguro (útil para executar migrações quando SSH não estiver disponível)
Route::get('/deploy/migrate/{token}', function ($token) {
    if (app()->environment('local') && env('ALLOW_LOCAL_DEPLOY_ROUTES') !== 'true') {
        return 'Operação não permitida no ambiente local. (Para testar localmente, defina ALLOW_LOCAL_DEPLOY_ROUTES=true no seu .env)';
    }

    $expectedToken = config('app.deploy_token');

    if (empty($expectedToken) || $token !== $expectedToken) {
        abort(403, 'Acesso não autorizado.');
    }

    try {
        Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migrações executadas com sucesso:<br><pre>' . Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Erro ao executar migrações: ' . $e->getMessage();
    }
});

// Nova rota para executar migrate:refresh (recria o banco de dados revertendo e reexecutando migrações)
Route::get('/deploy/migrate-refresh/{token}', function ($token) {
    if (app()->environment('local') && env('ALLOW_LOCAL_DEPLOY_ROUTES') !== 'true') {
        return 'Operação não permitida no ambiente local. (Para testar localmente, defina ALLOW_LOCAL_DEPLOY_ROUTES=true no seu .env)';
    }

    $expectedToken = config('app.deploy_token');

    if (empty($expectedToken) || $token !== $expectedToken) {
        abort(403, 'Acesso não autorizado.');
    }

    try {
        Illuminate\Support\Facades\Artisan::call('migrate:refresh', ['--force' => true]);
        return 'Banco de dados resetado (refresh) e migrado com sucesso:<br><pre>' . Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Erro ao executar migrate:refresh: ' . $e->getMessage();
    }
});

// Nova rota para executar migrate:fresh (recria o banco de dados dropando todas as tabelas)
Route::get('/deploy/migrate-fresh/{token}', function ($token) {
    if (app()->environment('local') && env('ALLOW_LOCAL_DEPLOY_ROUTES') !== 'true') {
        return 'Operação não permitida no ambiente local. (Para testar localmente, defina ALLOW_LOCAL_DEPLOY_ROUTES=true no seu .env)';
    }

    $expectedToken = config('app.deploy_token');

    if (empty($expectedToken) || $token !== $expectedToken) {
        abort(403, 'Acesso não autorizado.');
    }

    try {
        Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        return 'Banco de dados recriado (fresh) e migrado com sucesso:<br><pre>' . Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Erro ao executar migrate:fresh: ' . $e->getMessage();
    }
});

// Nova rota para limpar cache em produção via cPanel (suporta com hífen ou underline)
$clearCacheCallback = function ($token) {
    if (app()->environment('local') && env('ALLOW_LOCAL_DEPLOY_ROUTES') !== 'true') {
        return 'Operação não permitida no ambiente local. (Para testar localmente, defina ALLOW_LOCAL_DEPLOY_ROUTES=true no seu .env)';
    }

    $expectedToken = config('app.deploy_token');

    if (empty($expectedToken) || $token !== $expectedToken) {
        abort(403, 'Acesso não autorizado.');
    }

    try {
        Illuminate\Support\Facades\Artisan::call('config:clear');
        Illuminate\Support\Facades\Artisan::call('cache:clear');
        Illuminate\Support\Facades\Artisan::call('view:clear');
        Illuminate\Support\Facades\Artisan::call('route:clear');
        return 'Todos os caches do Laravel foram limpos com sucesso!';
    } catch (\Exception $e) {
        return 'Erro ao limpar cache: ' . $e->getMessage();
    }
};

Route::get('/deploy/clear-cache/{token}', $clearCacheCallback);
Route::get('/deploy/clear_cache/{token}', $clearCacheCallback);

// Rota auxiliar para configurar o usuário administrador em produção
Route::get('/deploy/setup-admin/{token}', function ($token) {
    if (app()->environment('local') && env('ALLOW_LOCAL_DEPLOY_ROUTES') !== 'true') {
        return 'Operação não permitida no ambiente local. (Para testar localmente, defina ALLOW_LOCAL_DEPLOY_ROUTES=true no seu .env)';
    }

    $expectedToken = config('app.deploy_token');

    if (empty($expectedToken) || $token !== $expectedToken) {
        abort(403, 'Acesso não autorizado.');
    }

    try {
        // 1. Garantir que a role 'super_admin' exista no banco de dados de produção
        $role = Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // 2. Executar o comando do Shield para gerar todas as permissões e associá-las à role 'super_admin'
        Illuminate\Support\Facades\Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--option' => 'policies_and_permissions',
            '--no-interaction' => true
        ]);

        // 3. Associar a role ao primeiro usuário (ID 1 - Administrador SisGraxaria)
        $user = App\Models\User::find(1);
        if ($user) {
            $user->assignRole($role);
            return 'Perfil super_admin configurado, permissões geradas e atribuídas com sucesso ao Administrador (ID 1)!';
        }

        return 'Permissões geradas, mas Administrador (ID 1) não encontrado no banco de dados.';
    } catch (\Exception $e) {
        return 'Erro ao configurar perfil administrador: ' . $e->getMessage();
    }
});

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/sales/{sale}/certificate/pdf', function (Sale $sale) {
    if ($sale->product_type === 'Sebo') {
        $certificate = $sale->tallowCertificates()->first();
        if (!$certificate) {
            abort(404, 'Laudo de Sebo não encontrado.');
        }
        
        $pdf = Pdf::loadView('pdf.tallow_certificate', [
            'sale' => $sale,
            'cert' => $certificate,
        ]);
        return $pdf->download("laudo_sebo_venda_{$sale->id}.pdf");
        
    } elseif ($sale->product_type === 'Farinha') {
        $certificate = $sale->mealCertificates()->first();
        if (!$certificate) {
            abort(404, 'Laudo de Farinha não encontrado.');
        }
        
        $pdf = Pdf::loadView('pdf.meal_certificate', [
            'sale' => $sale,
            'cert' => $certificate,
        ]);
        return $pdf->download("laudo_farinha_venda_{$sale->id}.pdf");
    }
    
    abort(400, 'Tipo de produto inválido para emissão de laudo.');
})->name('sales.certificate.pdf');

// Módulo Mobile para Motoristas
Route::prefix('mobile')->group(function () {
    Route::get('/login', [App\Http\Controllers\MobileController::class, 'showLogin'])->name('mobile.login');
    Route::post('/login', [App\Http\Controllers\MobileController::class, 'login'])->name('mobile.login.post');
    Route::post('/logout', [App\Http\Controllers\MobileController::class, 'logout'])->name('mobile.logout');

    Route::middleware(App\Http\Middleware\MobileDriverMiddleware::class)->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\MobileController::class, 'dashboard'])->name('mobile.dashboard');
        Route::get('/checkin', [App\Http\Controllers\MobileController::class, 'showCheckin'])->name('mobile.checkin');
        Route::post('/checkin', [App\Http\Controllers\MobileController::class, 'checkin'])->name('mobile.checkin.post');
        Route::get('/checkout', [App\Http\Controllers\MobileController::class, 'showCheckout'])->name('mobile.checkout');
        Route::post('/checkout', [App\Http\Controllers\MobileController::class, 'checkout'])->name('mobile.checkout.post');
        Route::get('/vehicle/{id}/odometer', [App\Http\Controllers\MobileController::class, 'getVehicleOdometer'])->name('mobile.vehicle.odometer');
        Route::get('/collection', [App\Http\Controllers\MobileController::class, 'showCollection'])->name('mobile.collection');
        Route::post('/collection', [App\Http\Controllers\MobileController::class, 'collection'])->name('mobile.collection.post');
        Route::get('/commissions', [App\Http\Controllers\MobileController::class, 'commissions'])->name('mobile.commissions');
    });
});



