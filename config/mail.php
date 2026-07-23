<?php
/* Each SMTP account needs its own Gmail App Password. */
const OTP_ACCOUNTS = [
    'default' => [
        'from' => 'measm2519@gmail.com',
        'from_name' => 'Employee Management System',
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => 'measm2519@gmail.com',
        'smtp_password' => 'kbdg ifcz nvyb ugmv',
    ],
    'backup' => [
        'from' => 'ngetmeas285@gmail.com',
        'from_name' => 'Employee Management System',
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => 'ngetmeas285@gmail.com',
        'smtp_password' => 'xxfs mfby makf aipi',
    ],
];

// Change this to 'backup' to send all OTP emails with the backup account.
const OTP_DEFAULT_ACCOUNT = 'default';

function send_otp_email(string $recipient, string $name, string $otpCode, string $accountKey = OTP_DEFAULT_ACCOUNT): bool
{
    $account = OTP_ACCOUNTS[$accountKey] ?? null;
    if ($account === null) {
        return false;
    }

    $subject = 'Your Employee Management System login code';
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $message = '<p>Hello ' . $safeName . ',</p>'
        . '<p>Your login verification code is:</p>'
        . '<p style="font-size:28px;font-weight:bold;letter-spacing:6px">' . $otpCode . '</p>'
        . '<p>This code expires in 10 minutes. Do not share it with anyone.</p>';

    $autoload = __DIR__ . '/../vendor/autoload.php';
    if ($account['smtp_host'] !== '' && !is_file($autoload)) {
        return false;
    }

    if ($account['smtp_host'] !== '' && is_file($autoload)) {
        require_once $autoload;

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $account['smtp_host'];
                $mail->Port = $account['smtp_port'];
                $mail->SMTPAuth = $account['smtp_username'] !== '';
                $mail->Username = $account['smtp_username'];
                $mail->Password = $account['smtp_password'];
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->CharSet = 'UTF-8';
                $mail->setFrom($account['from'], $account['from_name']);
                $mail->addAddress($recipient, $name);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->AltBody = "Your Employee Management System login code is {$otpCode}. It expires in 10 minutes.";
                return $mail->send();
            } catch (Throwable $exception) {
                return false;
            }
        }
    }

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $account['from_name'] . ' <' . $account['from'] . '>',
    ];

    return mail($recipient, $subject, $message, implode("\r\n", $headers));
}
