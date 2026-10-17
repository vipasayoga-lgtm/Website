<?php

namespace App\Core;

final class Mailer
{
    public static function send(string $to, string $subject, string $bodyHtml): bool
    {
        $config = (require dirname(__DIR__) . '/Config/app.php')['mail'];

        if (($config['driver'] ?? 'log') === 'log') {
            return self::logToFile($to, $subject, $bodyHtml);
        }

        if (($config['driver'] ?? 'log') === 'smtp' && !empty($config['host'])) {
            return self::sendSmtp($to, $subject, $bodyHtml, $config);
        }

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= sprintf("From: %s <%s>\r\n", $config['from_name'] ?? 'Vipasa Yoga', $config['from_address'] ?? 'no-reply@vipasa.demo');

        return @mail($to, $subject, $bodyHtml, $headers);
    }

    private static function logToFile(string $to, string $subject, string $bodyHtml): bool
    {
        $logPath = dirname(__DIR__, 2) . '/storage/logs/mail.log';
        $entry = sprintf(
            "[%s] To: %s | Subject: %s\n%s\n%s\n\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            str_repeat('-', 60),
            strip_tags($bodyHtml)
        );

        return file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX) !== false;
    }

    private static function sendSmtp(string $to, string $subject, string $bodyHtml, array $config): bool
    {
        $host = (string) ($config['host'] ?? '');
        $port = (int) ($config['port'] ?? 587);
        $username = (string) ($config['username'] ?? '');
        $password = (string) ($config['password'] ?? '');
        $fromAddress = (string) ($config['from_address'] ?? 'no-reply@vipasa.demo');
        $fromName = (string) ($config['from_name'] ?? 'Vipasa Yoga');

        $remote = ($port === 465) ? 'ssl://' . $host : $host;

        $socket = @fsockopen($remote, $port, $errno, $errstr, 15);
        if (!$socket) {
            self::logToFile($to, "[SMTP ERROR {$errno}] {$errstr}", "Failed to connect to {$remote}:{$port}");
            return false;
        }

        stream_set_timeout($socket, 15);

        $readResponse = static function ($socket): string {
            $response = '';
            while ($line = fgets($socket, 512)) {
                $response .= $line;
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            return $response;
        };

        $sendCommand = static function ($socket, string $cmd) use ($readResponse): string {
            fputs($socket, $cmd . "\r\n");
            return $readResponse($socket);
        };

        $banner = $readResponse($socket);
        if (substr($banner, 0, 3) !== '220') {
            fclose($socket);
            self::logToFile($to, "[SMTP ERROR] Banner failed", $banner);
            return false;
        }

        $res = $sendCommand($socket, "EHLO localhost");
        if (substr($res, 0, 3) !== '250') {
            $res = $sendCommand($socket, "HELO localhost");
        }

        if ($port === 587) {
            $res = $sendCommand($socket, "STARTTLS");
            if (substr($res, 0, 3) === '220') {
                $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
                if (!$crypto) {
                    fclose($socket);
                    self::logToFile($to, "[SMTP ERROR] TLS negotiation failed", "STARTTLS crypto error");
                    return false;
                }
                $sendCommand($socket, "EHLO localhost");
            }
        }

        if ($username !== '' && $password !== '') {
            $res = $sendCommand($socket, "AUTH LOGIN");
            if (substr($res, 0, 3) === '334') {
                $res = $sendCommand($socket, base64_encode($username));
            }
            if (substr($res, 0, 3) === '334') {
                $res = $sendCommand($socket, base64_encode($password));
            }
            if (substr($res, 0, 3) !== '235') {
                fclose($socket);
                self::logToFile($to, "[SMTP ERROR] Authentication failed", $res);
                return false;
            }
        }

        $res = $sendCommand($socket, "MAIL FROM: <{$fromAddress}>");
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            self::logToFile($to, "[SMTP ERROR] MAIL FROM failed", $res);
            return false;
        }

        $res = $sendCommand($socket, "RCPT TO: <{$to}>");
        if (substr($res, 0, 3) !== '250' && substr($res, 0, 3) !== '251') {
            fclose($socket);
            self::logToFile($to, "[SMTP ERROR] RCPT TO failed", $res);
            return false;
        }

        $res = $sendCommand($socket, "DATA");
        if (substr($res, 0, 3) !== '354') {
            fclose($socket);
            self::logToFile($to, "[SMTP ERROR] DATA command failed", $res);
            return false;
        }

        $headers = [];
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = sprintf("From: %s <%s>", $fromName, $fromAddress);
        $headers[] = "To: <{$to}>";
        $headers[] = "Subject: {$subject}";
        $headers[] = "Date: " . date(DATE_RFC2822);
        $headers[] = "Message-ID: <" . time() . '.' . uniqid('', true) . "@vipasa.demo>";

        $messageContent = implode("\r\n", $headers) . "\r\n\r\n" . $bodyHtml;
        $messageContent = str_replace("\r\n.", "\r\n..", $messageContent);

        fputs($socket, $messageContent . "\r\n.\r\n");
        $res = $readResponse($socket);

        $sendCommand($socket, "QUIT");
        fclose($socket);

        if (substr($res, 0, 3) !== '250') {
            self::logToFile($to, "[SMTP ERROR] Send message failed", $res);
            return false;
        }

        return true;
    }

    public static function sendLoginNotification(array $user, string $loginMethod = 'Email / Password'): bool
    {
        if (empty($user['email'])) {
            return false;
        }

        $config = require dirname(__DIR__) . '/Config/app.php';
        $appUrl = rtrim($config['url'] ?? 'http://localhost:8000', '/');
        $userName = htmlspecialchars($user['name'] ?? 'Yogi', ENT_QUOTES, 'UTF-8');
        $userEmail = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
        $timeStr = date('d M Y, g:i A T');
        $methodStr = htmlspecialchars($loginMethod, ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="UTF-8">
          <title>Successful Login Notification</title>
        </head>
        <body style="margin:0;padding:0;background-color:#f8fafc;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;padding:30px 15px;">
            <tr>
              <td align="center">
                <table width="100%" max-width="560" border="0" cellspacing="0" cellpadding="0" style="max-width:560px;background-color:#ffffff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.05);">
                  <tr>
                    <td style="background:linear-gradient(135deg, #1d332a 0%, #2b4c3f 100%);padding:28px 30px;text-align:center;">
                      <h1 style="color:#fdfbf7;font-size:22px;margin:0;font-weight:700;letter-spacing:-0.02em;">Vipasa Yoga</h1>
                      <p style="color:#d4a373;font-size:12px;margin:4px 0 0 0;text-transform:uppercase;letter-spacing:0.08em;font-weight:600;">Breathe. Move. Belong.</p>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:32px 30px;">
                      <div style="display:inline-block;padding:6px 12px;background-color:#ecfdf5;color:#047857;font-size:12px;font-weight:700;border-radius:20px;margin-bottom:16px;">
                        ✓ SUCCESSFUL LOGIN DETECTED
                      </div>
                      <h2 style="color:#1e2923;font-size:18px;margin:0 0 12px 0;font-weight:700;">Hello {$userName},</h2>
                      <p style="color:#475569;font-size:14px;line-height:1.6;margin:0 0 20px 0;">
                        We noticed a successful login to your Vipasa Yoga account.
                      </p>
                      
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;border-radius:12px;padding:16px 20px;margin-bottom:24px;border:1px solid #edf2f7;">
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;width:40%;">Account Email:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-weight:600;">{$userEmail}</td>
                        </tr>
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;">Login Time:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-weight:600;">{$timeStr}</td>
                        </tr>
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;">Login Method:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-weight:600;">{$methodStr}</td>
                        </tr>
                      </table>

                      <p style="color:#64748b;font-size:13px;line-height:1.5;margin:0 0 24px 0;">
                        If this was you, no further action is needed. If you did not log in, please reset your password or contact studio support immediately.
                      </p>

                      <div style="text-align:center;">
                        <a href="{$appUrl}/dashboard" style="display:inline-block;background-color:#e07a5f;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;box-shadow:0 4px 12px rgba(224,122,95,0.3);">
                          Go to Dashboard &rarr;
                        </a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td style="background-color:#f8fafc;padding:18px 30px;text-align:center;border-top:1px solid #e2e8f0;color:#94a3b8;font-size:12px;">
                      © Vipasa Yoga Studio. All rights reserved.
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>
        HTML;

        return self::send($user['email'], 'Security Alert: Successful Login to Vipasa Yoga', $html);
    }

    public static function sendPaymentInvoiceEmail(array $booking, ?string $transactionId = null): bool
    {
        $client = \App\Models\User::find((int) $booking['client_id']);
        if (!$client || empty($client['email'])) {
            return false;
        }

        $config = require dirname(__DIR__) . '/Config/app.php';
        $appUrl = rtrim($config['url'] ?? 'http://localhost:8000', '/');

        $instructor = \App\Models\Instructor::findWithName((int) $booking['instructor_id']);
        $package = \App\Models\Package::find((int) $booking['package_id']);

        $clientName = htmlspecialchars($client['name'], ENT_QUOTES, 'UTF-8');
        $ref = htmlspecialchars($booking['booking_ref'], ENT_QUOTES, 'UTF-8');
        $dateLabel = htmlspecialchars(date('l, d M Y', strtotime($booking['slot_date'])), ENT_QUOTES, 'UTF-8');
        $timeLabel = htmlspecialchars(date('g:i A', strtotime($booking['start_time'])), ENT_QUOTES, 'UTF-8');
        $packageName = htmlspecialchars($package['name'] ?? 'Yoga Session', ENT_QUOTES, 'UTF-8');
        $instructorName = htmlspecialchars($instructor['name'] ?? 'Instructor', ENT_QUOTES, 'UTF-8');
        $amountStr = htmlspecialchars($booking['currency'] . ' ' . number_format((float) $booking['total_amount'], 2), ENT_QUOTES, 'UTF-8');
        $txnIdStr = htmlspecialchars($transactionId ?: ($booking['booking_ref'] . '_TXN'), ENT_QUOTES, 'UTF-8');
        $invoiceUrl = $appUrl . '/booking/' . $booking['booking_ref'] . '/invoice';
        $successUrl = $appUrl . '/booking/' . $booking['booking_ref'] . '/success';

        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="UTF-8">
          <title>Payment Successful & Official Invoice</title>
        </head>
        <body style="margin:0;padding:0;background-color:#f8fafc;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;padding:30px 15px;">
            <tr>
              <td align="center">
                <table width="100%" max-width="600" border="0" cellspacing="0" cellpadding="0" style="max-width:600px;background-color:#ffffff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.05);">
                  <tr>
                    <td style="background:linear-gradient(135deg, #1d332a 0%, #2b4c3f 100%);padding:28px 30px;text-align:center;">
                      <h1 style="color:#fdfbf7;font-size:24px;margin:0;font-weight:700;letter-spacing:-0.02em;">Vipasa Yoga</h1>
                      <p style="color:#d4a373;font-size:12px;margin:4px 0 0 0;text-transform:uppercase;letter-spacing:0.08em;font-weight:600;">Official Payment Invoice & Receipt</p>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:32px 30px;">
                      <div style="display:inline-block;padding:6px 14px;background-color:#ecfdf5;color:#047857;font-size:12px;font-weight:700;border-radius:20px;margin-bottom:16px;">
                        ✓ PAYMENT FINISHED &amp; CONFIRMED
                      </div>
                      <h2 style="color:#1e2923;font-size:18px;margin:0 0 12px 0;font-weight:700;">Dear {$clientName},</h2>
                      <p style="color:#475569;font-size:14px;line-height:1.6;margin:0 0 20px 0;">
                        Thank you for your payment! Your booking has been successfully confirmed. Below are your session details and payment invoice summary:
                      </p>
                      
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;border:1px solid #edf2f7;">
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;width:40%;">Booking Reference:</td>
                          <td style="padding:6px 0;color:#2b4c3f;font-size:13px;font-weight:700;font-family:monospace;">#{$ref}</td>
                        </tr>
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;">Package / Style:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-weight:600;">{$packageName}</td>
                        </tr>
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;">Instructor:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-weight:600;">{$instructorName}</td>
                        </tr>
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;">Session Date &amp; Time:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-weight:600;">{$dateLabel} at {$timeLabel}</td>
                        </tr>
                        <tr>
                          <td style="padding:6px 0;color:#64748b;font-size:13px;">Transaction ID:</td>
                          <td style="padding:6px 0;color:#1e2923;font-size:13px;font-family:monospace;">{$txnIdStr}</td>
                        </tr>
                        <tr style="border-top:1px solid #e2e8f0;">
                          <td style="padding:12px 0 0 0;color:#1e2923;font-size:14px;font-weight:700;">Total Amount Paid:</td>
                          <td style="padding:12px 0 0 0;color:#e07a5f;font-size:16px;font-weight:700;">{$amountStr}</td>
                        </tr>
                      </table>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:12px;">
                        <tr>
                          <td align="center" style="padding-bottom:12px;">
                            <a href="{$invoiceUrl}" target="_blank" style="display:inline-block;background-color:#e07a5f;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;box-shadow:0 4px 12px rgba(224,122,95,0.3);">
                              📄 Download / View Official Invoice
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td align="center">
                            <a href="{$successUrl}" style="color:#2b4c3f;text-decoration:underline;font-size:13px;font-weight:600;">
                              View Session Details in Portal &rarr;
                            </a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="background-color:#f8fafc;padding:18px 30px;text-align:center;border-top:1px solid #e2e8f0;color:#94a3b8;font-size:12px;">
                      © Vipasa Yoga Studio. Thank you for practicing with us.
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>
        HTML;

        return self::send($client['email'], 'Payment Successful & Invoice — Vipasa Yoga [' . $ref . ']', $html);
    }
}

