<?php

// Set timezone
date_default_timezone_set("Europe/Paris");

$site_name = "Minkyu's Auction Site";

// Enable/disable debug mode
$debug = filter_var(getenv('APP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN);

function appBasePath() {
    static $base_path = null;

    if ($base_path !== null) {
        return $base_path;
    }

    $configured_path = getenv('APP_BASE_PATH');
    if ($configured_path !== false && $configured_path !== '') {
        $configured_path = '/' . trim($configured_path, '/');
        $base_path = $configured_path === '/' ? '' : $configured_path;
        return $base_path;
    }

    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    $script_dir = str_replace('\\', '/', dirname($script_name));
    $base_path = ($script_dir === '/' || $script_dir === '.') ? '' : rtrim($script_dir, '/');

    return $base_path;
}

function appUrl($path = '') {
    $path = ltrim($path, '/');
    $base_path = appBasePath();

    if ($path === '') {
        return $base_path === '' ? '/' : $base_path . '/';
    }

    return $base_path === '' ? '/' . $path : $base_path . '/' . $path;
}

function appAbsoluteUrl($path = '') {
    $is_https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $is_https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? (getenv('APP_HOST') ?: 'localhost');

    return $scheme . '://' . $host . appUrl($path);
}

function redirectTo($path) {
    header('Location: ' . appUrl($path));
    exit;
}

function appStoragePath($path = '') {
    $storage_root = dirname(__DIR__) . '/storage';

    if ($path === '') {
        return $storage_root;
    }

    return $storage_root . '/' . ltrim($path, '/');
}
