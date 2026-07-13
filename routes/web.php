<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

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

