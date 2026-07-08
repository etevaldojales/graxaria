<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rota auxiliar de deploy seguro (útil para executar migrações quando SSH não estiver disponível)
Route::get('/deploy/migrate/{token}', function ($token) {
    if (app()->environment('local')) {
        return 'Operação não permitida no ambiente local.';
    }

    $expectedToken = config('app.deploy_token') ?? env('DEPLOY_TOKEN');

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
