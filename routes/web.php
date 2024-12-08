<?php

use App\Services\PermissionGenerateService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $permission = new PermissionGenerateService();
    $permission->handle();
    return view('welcome');
});
