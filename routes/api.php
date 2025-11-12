<?php

use App\Http\Controllers\Api\AuthController;

Route::post('/admin/portal_login', [AuthController::class, 'loginAction']);