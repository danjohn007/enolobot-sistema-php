<?php
// Diagnóstico simple
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Test Simple</title></head><body>";
echo "<h1>Test de Diagnóstico Simple</h1>";
echo "<hr>";

// Test 1: PHP funciona
echo "<h2>1. PHP está funcionando</h2>";
echo "<p style='color: green;'>✓ PHP versión: " . phpversion() . "</p>";

// Test 2: Variables de servidor
echo "<h2>2. Variables del Servidor</h2>";
echo "<pre>";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "</pre>";

// Test 3: Intentar cargar config
echo "<h2>3. Intentando cargar config.php</h2>";
try {
    if (file_exists(__DIR__ . '/config/config.php')) {
        echo "<p style='color: green;'>✓ config.php existe</p>";
        require_once __DIR__ . '/config/config.php';
        echo "<p style='color: green;'>✓ config.php cargado correctamente</p>";
        echo "<p>BASE_URL: <strong>" . (defined('BASE_URL') ? BASE_URL : 'NO DEFINIDO') . "</strong></p>";
    } else {
        echo "<p style='color: red;'>✗ config.php NO existe</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error al cargar config: " . $e->getMessage() . "</p>";
}

// Test 4: Test de base de datos
echo "<h2>4. Test de Conexión a Base de Datos</h2>";
try {
    if (defined('DB_HOST')) {
        echo "<p>Host: " . DB_HOST . "</p>";
        echo "<p>Database: " . DB_NAME . "</p>";
        echo "<p>User: " . DB_USER . "</p>";
        
        $db = Database::getInstance();
        echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Constantes de DB no definidas</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión DB: " . $e->getMessage() . "</p>";
}

// Test 5: Archivos críticos
echo "<h2>5. Archivos Críticos</h2>";
$files = [
    'core/Router.php',
    'core/Controller.php',
    'core/Database.php',
    'app/controllers/AuthController.php',
    'app/views/auth/login.php',
    'app/views/layouts/header.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ $file</p>";
    } else {
        echo "<p style='color: red;'>✗ $file NO EXISTE</p>";
    }
}

echo "</body></html>";
