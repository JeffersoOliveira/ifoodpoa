<?php

use App\Services\PermissionGenerateService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $permission = new PermissionGenerateService();
    $permission->handle();
    return view('welcome');
});

//TESTE PARA GERAR LOG
// use Illuminate\Support\Facades\Log;

// Route::get('/test-log', function () {
//     Log::info('🚀 Testando logs do Laravel!');
//     return 'Log gerado!';
// });
