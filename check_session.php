<?php
session_start();
echo "<h1>Información de Sesión</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['role'])) {
    echo "<h2>Tu rol es: <strong>" . $_SESSION['role'] . "</strong></h2>";
} else {
    echo "<h2>No hay rol definido</h2>";
}
