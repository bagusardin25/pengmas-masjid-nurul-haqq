<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Folder publik = folder tempat file ini berada. Di shared hosting isi folder public dipindah ke
// public_html, jadi storage link, build Vite, dan public_path() harus mengikuti folder ini.
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
