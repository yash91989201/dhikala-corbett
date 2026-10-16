<?php
// Test script WITHOUT reCAPTCHA
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== FORM TEST WITHOUT reCAPTCHA ===<br><br>";

// Simulate POST data
$_POST = [
    'formtype' => 'horizontal',
    'returnurl' => '../thankyou.html',
    'hotel_type' => 'Bijrani Forest Rest House',
    'LL_duration' => '4',
    'LL_arrival' => '2026-08-13',
    'LL_adult' => '7+',
    'LL_requirements' => 'Test requirements',
    'LL_name' => 'Rajesh Kumar',
    'LL_fromcountry' => 'India',
    'LL_email' => 'rajeshtechvision007@gmail.com',
    'LL_phone' => '7982706650',
];

// Load vendor
$vendor_path = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendor_path)) {
    die("❌ Vendor not found at: " . $vendor_path);
}

require_once $vendor_path;
echo "✅ Vendor loaded<br>";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Skip reCAPTCHA for this test
echo "Skipping reCAPTCHA for test...<br>";

// Send email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.dhikala-corbett.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mail@dhikala-corbett.com';
    $mail->Password = '25May@2026#';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    $mail->setFrom('mail@dhikala-corbett.com', 'The Indian Voyage');
    $mail->addAddress('rajesh@techvisionindia.com');
    $mail->addReplyTo($_POST['LL_email']);
    
    $mail->isHTML(true);
    $mail->Subject = 'Test Enquiry from ' . $_SERVER['HTTP_HOST'];
    $mail->Body = "<h2>Test Enquiry</h2>
        <p><strong>Name:</strong> " . $_POST['LL_name'] . "</p>
        <p><strong>Email:</strong> " . $_POST['LL_email'] . "</p>
        <p><strong>Phone:</strong> " . $_POST['LL_phone'] . "</p>
        <p><strong>Requirements:</strong> " . $_POST['LL_requirements'] . "</p>";
    
    if ($mail->send()) {
        echo "✅ EMAIL SENT SUCCESSFULLY!<br>";
        echo "Check " . $_POST['LL_email'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Email failed: " . $mail->ErrorInfo . "<br>";
    echo "Exception: " . $e->getMessage() . "<br>";
}
?>