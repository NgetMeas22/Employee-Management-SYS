<?php
/*
 * Update these values before using SMTP. For Gmail, use an App Password,
 * never your normal account password.
 */
const OTP_MAIL_FROM = 'ngetmeas285@gmail.com';
const OTP_MAIL_FROM_NAME = 'Employee Management System';
const OTP_SMTP_HOST = 'smtp.gmail.com';
const OTP_SMTP_PORT = 587;
const OTP_SMTP_USERNAME = 'ngetmeas285@gmail.com';
// Paste a Google App Password here. Do not use your normal Gmail password.
const OTP_SMTP_PASSWORD = 'xxfs mfby makf aipi';



function send_otp_email(string $recipient, string $name, string $otpCode): bool
{
    $subject = 'Your Employee Management System login code';
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $message = '<p>Hello ' . $safeName . ',</p>'
        . '<p>Your login verification code is:</p>'
        . '<p style="font-size:28px;font-weight:bold;letter-spacing:6px">' . $otpCode . '</p>'
        . '<p>This code expires in 10 minutes. Do not share it with anyone.</p>';

    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (OTP_SMTP_HOST !== '' && !is_file($autoload)) {
        return false;
    }

    if (OTP_SMTP_HOST !== '' && is_file($autoload)) {
        require_once $autoload;

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = OTP_SMTP_HOST;
                $mail->Port = OTP_SMTP_PORT;
                $mail->SMTPAuth = OTP_SMTP_USERNAME !== '';
                $mail->Username = OTP_SMTP_USERNAME;
                $mail->Password = OTP_SMTP_PASSWORD;
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->CharSet = 'UTF-8';
                $mail->setFrom(OTP_MAIL_FROM, OTP_MAIL_FROM_NAME);
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
        'From: ' . OTP_MAIL_FROM_NAME . ' <' . OTP_MAIL_FROM . '>',
    ];

    return mail($recipient, $subject, $message, implode("\r\n", $headers));
}
