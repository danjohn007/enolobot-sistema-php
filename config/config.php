<?php
// Auto-detect base URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = str_replace(basename($script), '', $script);
    return $protocol . $host . $path;
}

// Base URL configuration
define('BASE_URL', rtrim(getBaseUrl(), '/'));

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'fix360_majorbot');
define('DB_USER', 'fix360_majorbot');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// System configuration
define('SITE_NAME', 'MajorBot - Sistema de Mayordomía Online');
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_METHOD', 'index');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Timezone
date_default_timezone_set('America/Mexico_City');
