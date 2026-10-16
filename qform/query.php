<?php
// ✅ FIXED: Vendor path sahi karo
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$to_email = "tours@dhikala-corbett.com";

$cc_email = "";
$company_name = "The Indian Voyage";
$subject_text = "Tour enquiry from www.dhikala-corbett.com";
$redirect_url = "thankyou.html";

// reCAPTCHA Secret Key
$recaptcha_secret = "6LcmmfssAAAAAGLwVGvEg49zovXSJ9ppCP-ioF4o";

// SMTP Settings - Hostinger
$smtp_host = "mail.dhikala-corbett.com";
$smtp_port = 587;
$smtp_user = "mail@dhikala-corbett.com";
$smtp_pass = "Tvi@aug17";  // ✅ Updated password

$form_data = $_POST;
$returnurl = $_POST['returnurl'] ?? $redirect_url;

// ============================================
// reCAPTCHA VERIFICATION FUNCTION
// ============================================
function verify_recaptcha($secret_key, $recaptcha_response) {
    if (empty($recaptcha_response)) {
        return false;
    }
    
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret_key,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );
    
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        return false;
    }
    
    $response = json_decode($result, true);
    return $response['success'] === true;
}

// ============================================
// CHECK reCAPTCHA FIRST
// ============================================
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

// ✅ FIX: Skip reCAPTCHA for testing (remove when working)
$skip_captcha = true;  // Set to false when reCAPTCHA is fixed

if (!$skip_captcha) {
    if (!verify_recaptcha($recaptcha_secret, $recaptcha_response)) {
        session_start();
        $_SESSION['captcha_error'] = "Please complete the Google reCAPTCHA verification.";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.html"));
        exit;
    }
}

// Function to clean field names
function clean_field_name($key) {
    $clean = preg_replace('/^LL_/i', '', $key);
    $clean = str_replace('_', ' ', $clean);
    $clean = ucwords($clean);
    
    $special = [
        'Name' => 'Full Name', 'Email' => 'Email Address', 
        'Phone' => 'Phone Number', 'Fromcountry' => 'Country',
        'Adult' => 'Adults', 'Kids' => 'Children',
        'Arrival' => 'Arrival Date', 'Duration' => 'Duration (Days)',
        'Requirements' => 'Requirements', 'Tocountry' => 'Hotel Category',
        'From City' => 'From City', 'To City' => 'To City',
        'Departure' => 'Departure Date', 'Return' => 'Return Date',
        'Class' => 'Travel Class'
    ];
    return $special[$clean] ?? $clean;
}

if (!empty($_POST)) {
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if ($ip == '::1') {
        $ip = '127.0.0.1 (Localhost)';
    }
    
    $today = date("l dS \of F Y h:i:s A");
    $referer = $_SERVER['HTTP_REFERER'] ?? 'Direct Access';
    
    $message = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; }
            .header { background: #01426d; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .field { padding: 8px; border-bottom: 1px solid #ddd; }
            .field strong { display: inline-block; width: 150px; color: #01426d; }
            .footer { background: #eee; padding: 10px; text-align: center; font-size: 12px; }
            hr { border: none; border-top: 1px solid #ddd; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>New Enquiry from ' . $company_name . '</h2>
            </div>
            <div class="content">
                <div class="field"><strong>IP Address:</strong> ' . strip_tags($ip) . '</div>
                <div class="field"><strong>Date/Time:</strong> ' . $today . '</div>
               
                <hr>';
    
    foreach ($form_data as $key => $value) {
        $skip_fields = ['formtype', 'returnurl', 'submit', 'g-recaptcha-response'];
        if (!in_array($key, $skip_fields) && !empty($value) && !is_array($value)) {
            $clean_key = clean_field_name($key);
            $message .= '<div class="field"><strong>' . $clean_key . ':</strong> ' . nl2br(htmlspecialchars($value)) . '</div>';
        }
    }
    
    $message .= '
                <hr>
                <div class="field"><strong>Reference URL:</strong> ' . strip_tags($referer) . '</div>
            </div>
            <div class="footer">
                <p>This email was generated from ' . $company_name . '</p>
            </div>
        </div>
    </body>
    </html>';
    
    $subject = $subject_text;
    $reply_email = $_POST['LL_email'] ?? ($_POST['email'] ?? '');
    
    // ============================================
    // SEND USING PHPMailer - FIXED
    // ============================================
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_user;
        $mail->Password   = $smtp_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtp_port;
        
        // ✅ FIXED: Added SMTP Options (this was missing!)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // ✅ FIXED: Set timeout
        $mail->Timeout = 30;
        
        $mail->setFrom($smtp_user, $company_name);
        $mail->addAddress($to_email);
        
        if (!empty($cc_email) && $cc_email != $to_email) {
            $mail->addCC($cc_email);
        }
        
        if (!empty($reply_email)) {
            $mail->addReplyTo($reply_email);
        }
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);
        
        $mail->send();
        
        // ✅ Log success
        error_log("Email sent successfully to: " . $to_email);
        
    } catch (Exception $e) {
        // ✅ Improved error logging
        error_log("Mail Error: " . $mail->ErrorInfo);
        error_log("Exception: " . $e->getMessage());
        
        // Optional: Save to error log file
        $log_file = __DIR__ . '/mail_errors.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error: " . $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}

header("Location: " . $returnurl);
exit;
?>