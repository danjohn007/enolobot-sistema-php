<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/SmtpMailer.php';

$testResult = null;
$errorMessage = '';
$diagnostics = [];
$toEmail = isset($_POST['to_email']) ? trim($_POST['to_email']) : (defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : '');

function smtp_socket_check($host, $port, $transport, $timeout) {
    $target = $transport . $host . ':' . (int)$port;
    $lastError = '';
    $start = microtime(true);

    set_error_handler(function ($severity, $message) use (&$lastError) {
        $lastError = $message;
        return true;
    });

    $socket = stream_socket_client($target, $errno, $errstr, $timeout);
    restore_error_handler();

    $elapsedMs = (int) round((microtime(true) - $start) * 1000);

    if ($socket) {
        fclose($socket);
        return [
            'target' => $target,
            'success' => true,
            'message' => 'Conexion abierta',
            'elapsed_ms' => $elapsedMs,
        ];
    }

    $message = trim($errstr);
    if ($message === '' && $lastError !== '') {
        $message = trim($lastError);
    }
    if ($message === '') {
        $resolvedHost = gethostbyname($host);
        if ($resolvedHost === $host) {
            $message = 'Host no resuelto: ' . $host;
        } else {
            $message = 'Sin respuesta del socket';
        }
    }

    return [
        'target' => $target,
        'success' => false,
        'message' => $message,
        'elapsed_ms' => $elapsedMs,
        'errno' => isset($errno) ? (int) $errno : 0,
    ];
}

function build_smtp_diagnostics() {
    $timeout = defined('SMTP_TIMEOUT') ? (int) SMTP_TIMEOUT : 20;
    $items = [];

    $transport = SMTP_ENCRYPTION === 'ssl' ? 'ssl://' : 'tcp://';
    $items['configured'] = smtp_socket_check(SMTP_HOST, SMTP_PORT, $transport, $timeout);

    if (SMTP_ENCRYPTION === 'ssl') {
        $items['tcp_same_port'] = smtp_socket_check(SMTP_HOST, SMTP_PORT, 'tcp://', $timeout);
        $items['tls_587'] = smtp_socket_check(SMTP_HOST, 587, 'tcp://', $timeout);
    } elseif (SMTP_ENCRYPTION === 'tls') {
        $items['ssl_465'] = smtp_socket_check(SMTP_HOST, 465, 'ssl://', $timeout);
    }

    $items['localhost_25'] = smtp_socket_check('localhost', 25, 'tcp://', $timeout);

    return $items;
}

function smtp_recommendation($diagnostics) {
    if (
        isset($diagnostics['configured']) &&
        $diagnostics['configured']['success'] &&
        SMTP_ENCRYPTION === 'tls' &&
        (int) SMTP_PORT === 587
    ) {
        return 'La configuracion activa funciona en TLS 587. Si ssl_465 o localhost_25 fallan, se considera normal en muchos hostings.';
    }

    if (isset($diagnostics['configured']) && !$diagnostics['configured']['success']) {
        $message = $diagnostics['configured']['message'];

        if (stripos($message, 'Host no resuelto') !== false || stripos($message, 'php_network_getaddresses') !== false) {
            return 'El host SMTP no resuelve desde el servidor. Verifica SMTP_HOST o prueba con mail.enolobot.digital.';
        }

        if (isset($diagnostics['tls_587']) && $diagnostics['tls_587']['success']) {
            return 'El puerto 587 responde. Prueba cambiar a SMTP_PORT = 587 y SMTP_ENCRYPTION = tls en la configuracion.';
        }

        if (isset($diagnostics['localhost_25']) && $diagnostics['localhost_25']['success']) {
            return 'El servidor local responde en localhost:25. Si el hosting bloquea salida SMTP externa, revisa si cPanel permite relay local.';
        }

        return 'La conexion SMTP no abre con la configuracion actual. El problema suele ser puerto bloqueado por el hosting, host incorrecto o cifrado equivocado.';
    }

    return 'Si la conexion abre pero el envio falla, el siguiente paso es revisar autenticacion, contraseña o restricciones del servidor SMTP.';
}

function smtp_expected_failure($label) {
    if ($label === 'localhost_25') {
        return true;
    }

    if ($label === 'ssl_465' && SMTP_ENCRYPTION === 'tls' && (int) SMTP_PORT === 587) {
        return true;
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnostics = build_smtp_diagnostics();

    try {
        if (!SMTP_ENABLED) {
            throw new Exception('SMTP esta deshabilitado en config/config.php (SMTP_ENABLED = false).');
        }

        if ($toEmail === '') {
            throw new Exception('Debes capturar un correo destino para la prueba.');
        }

        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo destino no es valido.');
        }

        $mailer = new SmtpMailer();
        $subject = 'Prueba SMTP - Enolobot (' . date('Y-m-d H:i:s') . ')';
        $htmlBody = '<h2>Prueba de envio SMTP exitosa</h2>'
            . '<p>Este mensaje confirma que la configuracion SMTP del sistema esta funcionando correctamente.</p>'
            . '<p><strong>Servidor:</strong> ' . htmlspecialchars(SMTP_HOST, ENT_QUOTES, 'UTF-8') . '</p>'
            . '<p><strong>Puerto:</strong> ' . (int) SMTP_PORT . '</p>'
            . '<p><strong>Fecha:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        $textBody = "Prueba de envio SMTP exitosa\n"
            . "Servidor: " . SMTP_HOST . "\n"
            . "Puerto: " . SMTP_PORT . "\n"
            . "Fecha: " . date('Y-m-d H:i:s') . "\n";

        $mailer->send($toEmail, $subject, $htmlBody, $textBody);
        $testResult = true;
    } catch (Exception $e) {
        $testResult = false;
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba SMTP - Enolobot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Prueba de Configuracion SMTP</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">Este formulario intenta enviar un correo real y tambien valida conectividad basica al servidor SMTP.</p>

                        <h6>Configuracion actual</h6>
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td><strong>SMTP_ENABLED</strong></td>
                                <td><?php echo SMTP_ENABLED ? 'true' : 'false'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP_HOST</strong></td>
                                <td><?php echo htmlspecialchars(SMTP_HOST, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP_PORT</strong></td>
                                <td><?php echo (int) SMTP_PORT; ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP_ENCRYPTION</strong></td>
                                <td><?php echo htmlspecialchars(SMTP_ENCRYPTION, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP_USERNAME</strong></td>
                                <td><?php echo htmlspecialchars(SMTP_USERNAME, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP_FROM_EMAIL</strong></td>
                                <td><?php echo htmlspecialchars(SMTP_FROM_EMAIL, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>SMTP_TIMEOUT</strong></td>
                                <td><?php echo defined('SMTP_TIMEOUT') ? (int) SMTP_TIMEOUT . 's' : '20s'; ?></td>
                            </tr>
                        </table>

                        <form method="post" class="mt-4">
                            <div class="mb-3">
                                <label for="to_email" class="form-label">Correo destino de prueba</label>
                                <input type="email" class="form-control" id="to_email" name="to_email" value="<?php echo htmlspecialchars($toEmail, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar correo de prueba</button>
                        </form>

                        <?php if ($testResult === true): ?>
                            <div class="alert alert-success mt-4">
                                <strong>Envio exitoso.</strong> Se envio el correo de prueba a <?php echo htmlspecialchars($toEmail, ENT_QUOTES, 'UTF-8'); ?>.
                            </div>
                        <?php elseif ($testResult === false): ?>
                            <div class="alert alert-danger mt-4">
                                <strong>La prueba fallo.</strong><br>
                                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($diagnostics)): ?>
                            <div class="mt-4">
                                <h6>Diagnostico de conectividad SMTP</h6>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Prueba</th>
                                            <th>Destino</th>
                                            <th>Resultado</th>
                                            <th>Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($diagnostics as $label => $diagnostic): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($diagnostic['target'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <?php if ($diagnostic['success']): ?>
                                                        <span class="badge bg-success">OK</span>
                                                    <?php elseif (smtp_expected_failure($label)): ?>
                                                        <span class="badge bg-secondary">No requerido</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Fallo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($diagnostic['message'], ENT_QUOTES, 'UTF-8'); ?>
                                                    (<?php echo (int) $diagnostic['elapsed_ms']; ?> ms)
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="alert alert-warning mb-0">
                                    <strong>Interpretacion sugerida:</strong><br>
                                    <?php echo htmlspecialchars(smtp_recommendation($diagnostics), ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
