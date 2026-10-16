<?php
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Untitled Document</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <?php
    
        $name = "happy2";
        $from = "tours@theindianvoyage.com";
        $to = 'k.gulshankr@gmail.com';  
        $subject = 'Test Sending 2';
        $message = 'You got a message from '. $name;
        $headers = array(
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset="UTF-8";',
            'Content-Transfer-Encoding: 7bit',
            'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
            'From: ' . $from,
            'Reply-To: ' . $from,
            'Return-Path: ' . $from
        );
        $mailsent = mail($to, $subject, $message, implode("\n", $headers));
        if($mailsent){
            echo "Success2";
        }else{
            echo "not sent";
        }
   
    ?>
    
</body>
</html>