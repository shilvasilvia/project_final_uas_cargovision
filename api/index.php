<?php

// Force Vercel flags
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

// Ensure APP_KEY and production settings are populated
$appKey = getenv('APP_KEY') ?: 'base64:xB72uLtEAxxlXK/ni4hUkSePfx7+vEqCR+l/CvHa1rg=';
putenv("APP_KEY={$appKey}");
$_ENV['APP_KEY'] = $appKey;
$_SERVER['APP_KEY'] = $appKey;

putenv("APP_ENV=production");
$_ENV['APP_ENV'] = 'production';
$_SERVER['APP_ENV'] = 'production';

putenv("APP_DEBUG=true");
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';

// Prepare writable /tmp storage structure for Vercel Serverless Functions
$tmpStorage = '/tmp/storage';
$dirs = [
    $tmpStorage,
    $tmpStorage . '/framework',
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
    '/tmp/database'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Copy pre-seeded SQLite database to writable /tmp
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database/database.sqlite';
if (!file_exists($targetDb) && file_exists($sourceDb)) {
    @copy($sourceDb, $targetDb);
} elseif (!file_exists($targetDb)) {
    @touch($targetDb);
}

putenv("VIEW_COMPILED_PATH={$tmpStorage}/framework/views");
putenv("DB_CONNECTION=sqlite");
putenv("DB_DATABASE={$targetDb}");
putenv("SESSION_DRIVER=cookie");
putenv("CACHE_STORE=array");

$_ENV['VIEW_COMPILED_PATH'] = "{$tmpStorage}/framework/views";
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $targetDb;
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_STORE'] = 'array';

$_SERVER['VIEW_COMPILED_PATH'] = "{$tmpStorage}/framework/views";
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $targetDb;
$_SERVER['SESSION_DRIVER'] = 'cookie';
$_SERVER['CACHE_STORE'] = 'array';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../public/index.php';
