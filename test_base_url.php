<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/config.php';

echo "<h1>Test de BASE_URL</h1>";
echo "<hr>";

echo "<h2>Variables del servidor:</h2>";
echo "<pre>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "</pre>";

echo "<h2>BASE_URL calculada:</h2>";
echo "<pre>";
echo BASE_URL . "\n";
echo "</pre>";

echo "<h2>Enlaces de prueba:</h2>";
echo "<a href='" . BASE_URL . "/auth/login'>Login</a><br>";
echo "<a href='" . BASE_URL . "/dashboard'>Dashboard</a><br>";
echo "<a href='" . BASE_URL . "'>Home</a><br>";

echo "<h2>Test de conexión a la base de datos:</h2>";
try {
    $db = Database::getInstance();
    echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . $e->getMessage() . "</p>";
}
