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
define('DB_NAME', 'enolobot_chatbot');
define('DB_USER', 'enolobot_chatbot');
define('DB_PASS', ';FY7mUvCtQ%d');
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

// Chatbot API security
define('CHATBOT_API_KEY', '91b2c9e5-8f1a-4d3a-9c7e-2b5f6a7d8e9f');

// SMTP basic configuration
define('SMTP_ENABLED', true);
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_ENCRYPTION', 'tls'); // tls, ssl, none
define('SMTP_USERNAME', 'citas@miccqueretaro.com');
define('SMTP_PASSWORD', 'cambia_esta_password');
define('SMTP_FROM_EMAIL', 'citas@miccqueretaro.com');
define('SMTP_FROM_NAME', 'Confirmaciones Chatbot');
