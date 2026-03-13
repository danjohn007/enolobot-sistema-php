<?php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - MajorBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Test de Conexión y Configuración</h3>
                    </div>
                    <div class="card-body">
                        <h5>1. URL Base del Sistema</h5>
                        <div class="alert alert-info">
                            <strong>URL Base detectada:</strong> <?php echo BASE_URL; ?>
                        </div>

                        <h5>2. Configuración de Base de Datos</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Host:</strong></td>
                                <td><?php echo DB_HOST; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Base de Datos:</strong></td>
                                <td><?php echo DB_NAME; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Usuario:</strong></td>
                                <td><?php echo DB_USER; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Charset:</strong></td>
                                <td><?php echo DB_CHARSET; ?></td>
                            </tr>
                        </table>

                        <h5>3. Test de Conexión a la Base de Datos</h5>
                        <?php
                        try {
                            require_once 'core/Database.php';
                            $db = Database::getInstance();
                            $conn = $db->getConnection();
                            echo '<div class="alert alert-success">';
                            echo '<i class="bi bi-check-circle"></i> <strong>¡Conexión exitosa!</strong><br>';
                            echo 'La base de datos está conectada correctamente.';
                            echo '</div>';
                            
                            // Test query
                            $result = $db->select("SELECT DATABASE() as db_name");
                            if ($result) {
                                echo '<div class="alert alert-info">';
                                echo '<strong>Base de datos actual:</strong> ' . $result[0]['db_name'];
                                echo '</div>';
                            }
                        } catch(Exception $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<i class="bi bi-x-circle"></i> <strong>Error de conexión:</strong><br>';
                            echo $e->getMessage();
                            echo '<br><br><strong>Solución:</strong>';
                            echo '<ul>';
                            echo '<li>Verifica que MySQL esté corriendo</li>';
                            echo '<li>Crea la base de datos ejecutando el script database.sql</li>';
                            echo '<li>Verifica las credenciales en config/config.php</li>';
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>

                        <h5>4. Configuración del Sistema</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Nombre del sitio:</strong></td>
                                <td><?php echo SITE_NAME; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Zona horaria:</strong></td>
                                <td><?php echo date_default_timezone_get(); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha/Hora actual:</strong></td>
                                <td><?php echo date('Y-m-d H:i:s'); ?></td>
                            </tr>
                        </table>

                        <div class="mt-4">
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Ir al Sistema</a>
                            <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-secondary">Ir al Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
