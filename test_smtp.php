<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting test...<br>";

// ✅ FIXED: Direct path to vendor in public_html
$autoload_path = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload_path)) {
    die("❌ autoload.php not found at: " . $autoload_path);
}

require_once $autoload_path;
echo "✅ autoload.php loaded successfully<br>";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    echo "Testing SMTP connection...<br>";
    
    $mail->isSMTP();
    $mail->Host       = 'mail.dhikala-corbett.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mail@dhikala-corbett.com';
    $mail->Password   = '25May@2026#';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    // Enable debug output
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {
        echo "SMTP: " . htmlspecialchars($str) . "<br>";
    };
    
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    $mail->setFrom('mail@dhikala-corbett.com', 'Test');
    $mail->addAddress('rajesh@techvisionindia.com');
    $mail->Subject = 'Test Email from Dhikala';
    $mail->Body    = 'This is a test email sent from ' . $_SERVER['HTTP_HOST'];
    
    echo "Attempting to send email...<br>";
    if ($mail->send()) {
        echo "✅ Email sent successfully! Check rajesh@techvisionindia.com";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $mail->ErrorInfo . "<br>";
    echo "Exception: " . $e->getMessage() . "<br>";
}
?>