<?php
// Debug file to diagnose routing issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
// El proyecto está en /home4/enolobot/public_html/
$projectRoot = __DIR__;
require_once $projectRoot . '/config/config.php';

echo "<h1>Sistema de Diagnóstico - EnoloBot</h1>";
echo "<hr>";

// Check 1: mod_rewrite status
echo "<h2>1. Estado de mod_rewrite</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p style='color: green;'>✓ mod_rewrite ESTÁ HABILITADO</p>";
    } else {
        echo "<p style='color: red;'>✗ mod_rewrite NO está habilitado</p>";
    }
    echo "<pre>";
    print_r($modules);
    echo "</pre>";
} else {
    echo "<p style='color: orange;'>⚠ No se puede determinar (función apache_get_modules no disponible)</p>";
}

// Check 2: .htaccess files exist
echo "<h2>2. Archivos .htaccess</h2>";
$htaccessFiles = [
    $projectRoot . '/.htaccess' => 'Raíz',
    $projectRoot . '/public/.htaccess' => 'Public'
];

foreach ($htaccessFiles as $file => $label) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✓ $label .htaccess existe</p>";
        echo "<pre>" . htmlspecialchars(file_get_contents($file)) . "</pre>";
    } else {
        echo "<p style='color: red;'>✗ $label .htaccess NO existe</p>";
    }
}

// Check 3: Controllers exist
echo "<h2>3. Controladores</h2>";
$controllers = [
    'Auth' => $projectRoot . '/app/controllers/AuthController.php',
    'Home' => $projectRoot . '/app/controllers/HomeController.php',
    'Dashboard' => $projectRoot . '/app/controllers/DashboardController.php'
];

foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ {$name}Controller.php existe</p>";
    } else {
        echo "<p style='color: red;'>✗ {$name}Controller.php NO existe</p>";
    }
}

// Check 4: Models exist
echo "<h2>4. Modelos</h2>";
$models = ['User', 'Hotel'];
foreach ($models as $model) {
    $path = $projectRoot . "/app/models/{$model}.php";
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ {$model}.php existe</p>";
    } else {
        echo "<p style='color: red;'>✗ {$model}.php NO existe</p>";
    }
}

// Check 5: Views exist
echo "<h2>5. Vistas</h2>";
$views = [
    'auth/login' => $projectRoot . '/app/views/auth/login.php',
    'layouts/header' => $projectRoot . '/app/views/layouts/header.php',
    'layouts/footer' => $projectRoot . '/app/views/layouts/footer.php'
];

foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ {$name}.php existe</p>";
    } else {
        echo "<p style='color: red;'>✗ {$name}.php NO existe</p>";
    }
}

// Check 6: URL parameter
echo "<h2>6. Variables de URL</h2>";
echo "<pre>";
echo "GET: " . print_r($_GET, true);
echo "SERVER['REQUEST_URI']: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SERVER['QUERY_STRING']: " . $_SERVER['QUERY_STRING'] . "\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "</pre>";

// Check 7: Try to load AuthController directly
echo "<h2>7. Intento de cargar AuthController</h2>";
try {
    require_once $projectRoot . '/core/Database.php';
    require_once $projectRoot . '/core/Model.php';
    require_once $projectRoot . '/core/Controller.php';
    require_once $projectRoot . '/app/controllers/AuthController.php';
    
    $auth = new AuthController();
    echo "<p style='color: green;'>✓ AuthController cargado correctamente</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error al cargar AuthController: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p>¿Cómo acceder?</p>";
echo "<ul>";
echo "<li>URL amigable: <a href='" . BASE_URL . "/auth/login'>Ir al login</a></li>";
echo "<li>URL directa: <a href='" . BASE_URL . "/public/index.php?url=auth/login'>Login directo</a></li>";
echo "</ul>";
?>
