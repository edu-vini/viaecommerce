<?php

if (PHP_SAPI === 'cli') {
    return;
}

$baseDir = '/var/www/html';
$autoload = $baseDir.'/vendor/autoload.php';

if (! is_file($autoload)) {
    return;
}

$directories = [
    $baseDir.'/vendor/laravel/framework/src/Illuminate/Foundation',
    $baseDir.'/vendor/laravel/framework/src/Illuminate/Routing',
    $baseDir.'/vendor/laravel/framework/src/Illuminate/Http',
    $baseDir.'/vendor/laravel/framework/src/Illuminate/Support',
    $baseDir.'/vendor/laravel/framework/src/Illuminate/Database',
    $baseDir.'/vendor/laravel/framework/src/Illuminate/View',
    $baseDir.'/vendor/livewire/livewire/src',
    $baseDir.'/vendor/filament',
    $baseDir.'/app',
];

$ignored = [
    '/Testing/',
    '/SupportTesting/',
    '/Tests/',
    '/tests/',
    '/stubs/',
    '/Commands/',
    'helpers.php',
    'functions.php',
    'Test.php',
    'TestCase.php',
];

foreach ($directories as $directory) {
    if (! is_dir($directory)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getPathname();
        $skip = false;

        foreach ($ignored as $pattern) {
            if (str_contains($path, $pattern)) {
                $skip = true;
                break;
            }
        }

        if (! $skip) {
            @opcache_compile_file($path);
        }
    }
}
