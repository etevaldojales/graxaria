<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

/*
// Rota auxiliar de deploy seguro (útil para executar migrações quando SSH não estiver disponível)
Route::get('/deploy/migrate/{token}', function ($token) {
    if (app()->environment('local')) {
        return 'Operação não permitida no ambiente local.';
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

// Nova rota para limpar cache em produção via cPanel (suporta com hífen ou underline)
$clearCacheCallback = function ($token) {
    if (app()->environment('local')) {
        return 'Operação não permitida no ambiente local.';
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
    if (app()->environment('local')) {
        return 'Operação não permitida no ambiente local.';
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
*/

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


