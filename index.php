<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$root = __DIR__ . '/..';   // This points to your Laravel root folder

// Maintenance mode check
if (file_exists($root . '/storage/framework/maintenance.php')) {
    require $root . '/storage/framework/maintenance.php';
}

// Autoload
require $root . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once $root . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
