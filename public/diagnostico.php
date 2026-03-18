<?php
// Diagnóstico directo - bypass del Router
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Diagnóstico</title></head><body>";
echo "<h1>Diagnóstico del Sistema</h1>";
echo "<hr>";

// Test 1: PHP funciona
echo "<h2>1. PHP Funciona</h2>";
echo "<p style='color: green;'>✓ PHP versión: " . phpversion() . "</p>";

// Test 2: Variables servidor
echo "<h2>2. Variables del Servidor</h2>";
echo "<pre>";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'N/A') . "\n";
echo "</pre>";

// Test 3: Cargar config
echo "<h2>3. Cargando config.php</h2>";
$configPath = __DIR__ . '/../config/config.php';
echo "<p>Ruta: $configPath</p>";

if (file_exists($configPath)) {
    echo "<p style='color: green;'>✓ config.php existe</p>";
    try {
        require_once $configPath;
        echo "<p style='color: green;'>✓ config.php cargado correctamente</p>";
        if (defined('BASE_URL')) {
            echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
        } else {
            echo "<p style='color: red;'>✗ BASE_URL no está definido</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "<p style='color: red;'>✗ config.php NO existe en: $configPath</p>";
}

// Test 4: Base de datos
echo "<h2>4. Conexión Base de Datos</h2>";
if (defined('DB_HOST')) {
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    echo "<p>User: " . DB_USER . "</p>";
    
    try {
        require_once __DIR__ . '/../core/Database.php';
        $db = Database::getInstance();
        echo "<p style='color: green;'>✓ Conexión exitosa</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠ Constantes DB no definidas</p>";
}

// Test 5: Archivos críticos
echo "<h2>5. Archivos del Sistema</h2>";
$rootDir = __DIR__ . '/..';
$files = [
    'core/Router.php',
    'core/Controller.php',
    'core/Database.php',
    'app/controllers/AuthController.php',
    'app/views/auth/login.php',
    'app/views/layouts/header.php',
    'app/views/layouts/footer.php'
];

foreach ($files as $file) {
    $path = $rootDir . '/' . $file;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ $file</p>";
    } else {
        echo "<p style='color: red;'>✗ $file NO EXISTE</p>";
    }
}

// Test 6: .htaccess
echo "<h2>6. Archivos .htaccess</h2>";
$htaccesses = [
    '../.htaccess' => 'Raíz',
    '.htaccess' => 'Public'
];

foreach ($htaccesses as $file => $label) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ $label .htaccess existe</p>";
        echo "<details><summary>Ver contenido</summary><pre>" . htmlspecialchars(file_get_contents($path)) . "</pre></details>";
    } else {
        echo "<p style='color: red;'>✗ $label .htaccess NO existe</p>";
    }
}

echo "</body></html>";
