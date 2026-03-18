<?php

class ChatbotController extends Controller {
    private $mailer;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../../core/SmtpMailer.php';
        $this->mailer = new SmtpMailer();
    }

    public function confirmacion() {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, X-Api-Key');
        header('Access-Control-Allow-Methods: POST, OPTIONS');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json([
                'ok' => false,
                'error' => 'method_not_allowed',
                'message' => 'Use POST'
            ], 405);
        }

        $apiKey = $this->getApiKeyFromHeaders();
        if ($apiKey !== CHATBOT_API_KEY) {
            return $this->json([
                'ok' => false,
                'error' => 'unauthorized',
                'message' => 'Invalid API key'
            ], 403);
        }

        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);
        if (!is_array($input)) {
            return $this->json([
                'ok' => false,
                'error' => 'invalid_json',
                'message' => 'Body must be a valid JSON object'
            ], 400);
        }

        $destino = trim($input['correo'] ?? '');
        $mensajeUsuario = trim($input['mensaje_usuario'] ?? ($input['mensaje'] ?? ''));

        if (!filter_var($destino, FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'ok' => false,
                'error' => 'invalid_email',
                'message' => 'correo is required and must be valid'
            ], 400);
        }

        if ($mensajeUsuario === '') {
            return $this->json([
                'ok' => false,
                'error' => 'missing_message',
                'message' => 'mensaje_usuario is required'
            ], 400);
        }

        $fecha = $this->extractDateAsDdMmYy($mensajeUsuario);
        if ($fecha === null) {
            return $this->json([
                'ok' => false,
                'error' => 'date_not_found',
                'message' => 'No date found in mensaje_usuario. Expected formats like 17/03/26 or 17-03-2026'
            ], 400);
        }

        $subject = 'ConfirmaciÃ³n';
        $texto = 'ConfirmaciÃ³n para ' . $fecha;
        $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>ConfirmaciÃ³n</title></head><body>'
            . '<p style="font-family:Arial,sans-serif;font-size:16px;">' . htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') . '</p>'
            . '</body></html>';

        try {
            $this->mailer->send($destino, $subject, $html, $texto);

            return $this->json([
                'ok' => true,
                'subject' => $subject,
                'texto' => $texto,
                'fecha_extraida' => $fecha,
                'destino' => $destino
            ]);
        } catch (Exception $e) {
            error_log('[CHATBOT_CONFIRMACION_SMTP_ERROR] ' . $e->getMessage());
            return $this->json([
                'ok' => false,
                'error' => 'smtp_send_failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function envio_correo() {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, X-Api-Key');
        header('Access-Control-Allow-Methods: POST, OPTIONS');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json([
                'ok' => false,
                'error' => 'method_not_allowed',
                'message' => 'Use POST'
            ], 405);
        }

        $apiKey = $this->getApiKeyFromHeaders();
        if ($apiKey !== CHATBOT_API_KEY) {
            return $this->json([
                'ok' => false,
                'error' => 'unauthorized',
                'message' => 'Invalid API key'
            ], 403);
        }

        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);
        if (!is_array($input)) {
            return $this->json([
                'ok' => false,
                'error' => 'invalid_json',
                'message' => 'Body must be a valid JSON object'
            ], 400);
        }

        $destino = trim($input['correo_destinatario'] ?? ($input['correo'] ?? ''));
        $payload = $input['payload'] ?? null;
        $subject = trim($input['asunto'] ?? 'Notificacion');

        if (!filter_var($destino, FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'ok' => false,
                'error' => 'invalid_email',
                'message' => 'correo_destinatario is required and must be valid'
            ], 400);
        }

        if ($payload === null || $payload === '') {
            return $this->json([
                'ok' => false,
                'error' => 'missing_payload',
                'message' => 'payload is required'
            ], 400);
        }

        if ($subject === '') {
            $subject = 'Notificacion';
        }

        $payloadText = '';
        if (is_array($payload)) {
            $payloadText = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $payloadText = (string)$payload;
        }

        if ($payloadText === false || $payloadText === '') {
            return $this->json([
                'ok' => false,
                'error' => 'invalid_payload',
                'message' => 'payload could not be converted to text'
            ], 400);
        }

        $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>'
            . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8')
            . '</title></head><body style="font-family:Arial,sans-serif;">'
            . '<h3 style="margin-bottom:12px;">Payload recibido</h3>'
            . '<pre style="background:#f5f5f5;padding:12px;border-radius:6px;white-space:pre-wrap;">'
            . htmlspecialchars($payloadText, ENT_QUOTES, 'UTF-8')
            . '</pre></body></html>';

        try {
            $this->mailer->send($destino, $subject, $html, $payloadText);

            return $this->json([
                'ok' => true,
                'destino' => $destino,
                'asunto' => $subject
            ]);
        } catch (Exception $e) {
            error_log('[CHATBOT_ENVIO_CORREO_SMTP_ERROR] ' . $e->getMessage());
            return $this->json([
                'ok' => false,
                'error' => 'smtp_send_failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function extractDateAsDdMmYy($text) {
        $pattern = '/\b(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})\b/';
        if (!preg_match($pattern, $text, $matches)) {
            return null;
        }

        $day = (int)$matches[1];
        $month = (int)$matches[2];
        $year = (int)$matches[3];

        if ($year < 100) {
            $year += 2000;
        }

        if (!checkdate($month, $day, $year)) {
            return null;
        }

        return sprintf('%02d/%02d/%02d', $day, $month, $year % 100);
    }

    private function getApiKeyFromHeaders() {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }

        if (isset($headers['X-Api-Key'])) {
            return trim($headers['X-Api-Key']);
        }

        if (isset($headers['x-api-key'])) {
            return trim($headers['x-api-key']);
        }

        if (!empty($_SERVER['HTTP_X_API_KEY'])) {
            return trim($_SERVER['HTTP_X_API_KEY']);
        }

        return '';
    }
}