<?php

class SmtpMailer {
    private $host;
    private $port;
    private $encryption;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;
    private $timeout;

    public function __construct($config = []) {
        $this->host = $config['host'] ?? SMTP_HOST;
        $this->port = (int)($config['port'] ?? SMTP_PORT);
        $this->encryption = strtolower($config['encryption'] ?? SMTP_ENCRYPTION);
        $this->username = $config['username'] ?? SMTP_USERNAME;
        $this->password = $config['password'] ?? SMTP_PASSWORD;
        $this->fromEmail = $config['from_email'] ?? SMTP_FROM_EMAIL;
        $this->fromName = $config['from_name'] ?? SMTP_FROM_NAME;
        $defaultTimeout = defined('SMTP_TIMEOUT') ? SMTP_TIMEOUT : 20;
        $this->timeout = (int)($config['timeout'] ?? $defaultTimeout);
    }

    public function send($toEmail, $subject, $htmlBody, $textBody = '') {
        if (!SMTP_ENABLED) {
            throw new Exception('SMTP is disabled in configuration');
        }

        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid destination email');
        }

        $transport = ($this->encryption === 'ssl') ? 'ssl://' : 'tcp://';
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'SNI_enabled' => true,
                'peer_name' => $this->host,
            ],
        ]);
        $socket = @stream_socket_client(
            $transport . $this->host . ':' . $this->port,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$socket) {
            throw new Exception('SMTP connection failed: ' . $errstr . ' (' . $errno . ')');
        }

        stream_set_timeout($socket, $this->timeout);

        try {
            $this->expectCode($socket, [220]);
            $this->sendCommand($socket, 'EHLO localhost', [250]);

            if ($this->encryption === 'tls') {
                $this->sendCommand($socket, 'STARTTLS', [220]);
                $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
                if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                    $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
                }
                if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                    $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
                }

                if (!stream_socket_enable_crypto($socket, true, $cryptoMethod)) {
                    throw new Exception('Unable to start TLS encryption');
                }
                $this->sendCommand($socket, 'EHLO localhost', [250]);
            }

            if ($this->username !== '' && $this->password !== '') {
                $this->sendCommand($socket, 'AUTH LOGIN', [334]);
                $this->sendCommand($socket, base64_encode($this->username), [334]);
                $this->sendCommand($socket, base64_encode($this->password), [235]);
            }

            $this->sendCommand($socket, 'MAIL FROM:<' . $this->fromEmail . '>', [250]);
            $this->sendCommand($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
            $this->sendCommand($socket, 'DATA', [354]);

            $mime = $this->buildMimeMessage($toEmail, $subject, $htmlBody, $textBody);
            fwrite($socket, $mime . "\r\n.\r\n");
            $this->expectCode($socket, [250]);

            $this->sendCommand($socket, 'QUIT', [221]);
            fclose($socket);

            return true;
        } catch (Exception $e) {
            if (is_resource($socket)) {
                @fwrite($socket, "QUIT\r\n");
                @fclose($socket);
            }
            throw $e;
        }
    }

    private function buildMimeMessage($toEmail, $subject, $htmlBody, $textBody) {
        $boundary = 'b_' . md5(uniqid((string)mt_rand(), true));

        if ($textBody === '') {
            $textBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody)));
        }

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $safeFromName = $this->encodeHeader($this->fromName);

        $headers = [];
        $headers[] = 'From: ' . $safeFromName . ' <' . $this->fromEmail . '>';
        $headers[] = 'To: <' . $toEmail . '>';
        $headers[] = 'Subject: ' . $encodedSubject;
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'Message-ID: <' . uniqid('', true) . '@' . $this->host . '>';

        $parts = [];
        $parts[] = '--' . $boundary;
        $parts[] = 'Content-Type: text/plain; charset=UTF-8';
        $parts[] = 'Content-Transfer-Encoding: 8bit';
        $parts[] = '';
        $parts[] = $textBody;
        $parts[] = '--' . $boundary;
        $parts[] = 'Content-Type: text/html; charset=UTF-8';
        $parts[] = 'Content-Transfer-Encoding: 8bit';
        $parts[] = '';
        $parts[] = $htmlBody;
        $parts[] = '--' . $boundary . '--';

        return implode("\r\n", $headers) . "\r\n\r\n" . implode("\r\n", $parts);
    }

    private function encodeHeader($value) {
        if ($value === '') {
            return '';
        }

        return '=?UTF-8?B?' . base64_encode($value) . '?=';
    }

    private function sendCommand($socket, $command, $expectedCodes) {
        fwrite($socket, $command . "\r\n");
        $this->expectCode($socket, $expectedCodes);
    }

    private function expectCode($socket, $expectedCodes) {
        $response = '';
        do {
            $line = fgets($socket, 515);
            if ($line === false) {
                throw new Exception('SMTP read failed or connection closed');
            }
            $response .= $line;
        } while (isset($line[3]) && $line[3] === '-');

        $code = (int)substr($response, 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            throw new Exception('SMTP unexpected response [' . $code . ']: ' . trim($response));
        }
    }
}