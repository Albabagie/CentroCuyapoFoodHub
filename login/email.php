<?php
require 'vendor\phpmailer\PHPMailer\src\Exception.php';
require 'vendor\phpmailer\PHPMailer\src\PHPMailer.php';
require 'vendor\phpmailer\PHPMailer\src\SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class EmailSender
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    public function sendVerificationEmail($email, $verification_code)
    {
        try {
            //Server settings
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'centrocuyapofoodhuba@gmail.com';
            $this->mail->Password = 'zspibwximocjqrwb';
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;

            //Recipients
            $this->mail->setFrom('centrocuyapofoodhuba@gmail.com', 'Food Hub | Centro Cuyapo');
            $this->mail->addAddress($email);

            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Email Verification Code';
            $this->mail->Body = "Your verification code is: $verification_code";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Usage:
$emailSender = new EmailSender();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    $email = trim($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        $verification_code = mt_rand(100000, 999999);

        if ($emailSender->sendVerificationEmail($email, $verification_code)) {
            session_start();
            $_SESSION["verification_code"] = $verification_code;
            $_SESSION["email"] = $email;
            header("Location: verify_email.php");
            exit;
        } else {
            $error = "Failed to send verification code.";
        }
    }
}
?>