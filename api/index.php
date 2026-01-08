<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// === FIX VERCEL READ-ONLY ===

// 1. Tentukan lokasi folder sementara di serverless
$tmpPath = '/tmp';
$storagePath = $tmpPath . '/storage';
$cachePath = $tmpPath . '/bootstrap/cache';

// 2. Buat folder jika belum ada (karena /tmp selalu kosong saat start)
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath . '/framework/views', 0777, true);
    mkdir($storagePath . '/framework/cache', 0777, true);
    mkdir($storagePath . '/framework/sessions', 0777, true);
    mkdir($storagePath . '/logs', 0777, true);
}

if (!is_dir($cachePath)) {
    mkdir($cachePath, 0777, true);
}

// 3. FORCE Laravel untuk menggunakan path /tmp ini
// Ini bagian yang kurang tadi: Mengarahkan file cache packages & services
$app->useStoragePath($storagePath);

// Kita set Environment Variable secara runtime agar Laravel tahu lokasinya berubah
putenv('APP_PACKAGES_CACHE=' . $cachePath . '/packages.php');
putenv('APP_SERVICES_CACHE=' . $cachePath . '/services.php');
putenv('APP_ROUTES_CACHE=' . $cachePath . '/routes.php');
putenv('APP_CONFIG_CACHE=' . $cachePath . '/config.php');

// ============================

$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate();