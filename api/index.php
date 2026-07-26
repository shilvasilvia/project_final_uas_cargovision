<?php

// Prepare writable /tmp directories for Vercel Serverless Functions
$tmpStorage = '/tmp/storage';
$dirs = [
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
    '/tmp/database'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Copy SQLite database to writable /tmp directory
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database/database.sqlite';
if (!file_exists($targetDb)) {
    if (file_exists($sourceDb)) {
        @copy($sourceDb, $targetDb);
    } else {
        @touch($targetDb);
    }
}

putenv("VIEW_COMPILED_PATH={$tmpStorage}/framework/views");
putenv("APP_SERVICES_CACHE={$tmpStorage}/framework/services.php");
putenv("APP_PACKAGES_CACHE={$tmpStorage}/framework/packages.php");
putenv("APP_CONFIG_CACHE={$tmpStorage}/framework/config.php");
putenv("APP_ROUTES_CACHE={$tmpStorage}/framework/routes.php");
putenv("DB_CONNECTION=sqlite");
putenv("DB_DATABASE={$targetDb}");

$_ENV['VIEW_COMPILED_PATH'] = "{$tmpStorage}/framework/views";
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $targetDb;
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $targetDb;

require __DIR__ . '/../public/index.php';
