<?php

declare(strict_types=1);

$runtimeDefaults = [
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'VIEW_COMPILED_PATH' => '/tmp/views',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'CACHE_STORE' => 'array',
    'QUEUE_CONNECTION' => 'sync',
];

foreach ($runtimeDefaults as $key => $value) {
    if (getenv($key) === false && ! array_key_exists($key, $_ENV) && ! array_key_exists($key, $_SERVER)) {
        $_ENV[$key] = $value;
    }
}

foreach (array_replace($_SERVER, $_ENV) as $key => $value) {
    if (
        is_string($key)
        && is_scalar($value)
        && preg_match('/^(APP|DB|SESSION|CACHE|QUEUE|LOG|MAIL|BROADCAST|FILESYSTEM|AWS|REDIS|MEMCACHED|VITE)_/', $key) === 1
    ) {
        putenv($key.'='.$value);
    }
}

if (! is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

require __DIR__.'/../public/index.php';
