<?php

namespace App\Http\Controllers\Common;


class SendMailUtility
{
    public static function sendMail($subject, $body, $listAddress) {
        SendMailUtility::executeSendMail($subject, $body, $listAddress);
    }

    private static function executeSendMail($subject, $body, $listAddress) {
        $mail = new \PHPMailer();

        $host = 'mail.smtp2go.com';
        $smtpPort = env('SMTP2GO_PORT', '587');
        $userName = env('SMTP2GO_USER', '');
        $password = env('SMTP2GO_PASS', '');

        $fromEmail = env('SMTP2GO_FROM_EMAIL', '');
        $fromName = env('SMTP2GO_FROM_NAME', '');

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $userName;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $smtpPort;                                    // TCP port to connect to

        $mail->setFrom($fromEmail, $fromName);

        foreach($listAddress as $address) {
            $mail->addAddress($address, $address);
        }

        $mail->Subject = $subject;
        $mail->Body    = $body;
        $result = $mail->send();

        if(!$result) {
            echo $mail->ErrorInfo;
        }
    }

    private static function sendSES() {
        // It's still in sandbox mode, so you need to verify the email
        $mail = new \PHPMailer();

        $host = 'email-smtp.us-west-2.amazonaws.com';
        $userName = 'AKIAIPRDF5CS3GPWNOJQ';
        $password = 'AjRucE8d+xpMXt0HL+41mDE+fU2nVX6Cwc9CD3yVu03U';

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $userName;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('eglifesoftware@gmail.com', 'eglifesoftware');
        $mail->addAddress('nguyenhoc89@gmail.com', 'nguyenhoc89');
        $mail->Subject = 'Try to use smtp2go';
        $mail->Body    = 'This is the email from smtp2go';
        $result = $mail->send();

        if(!$result) {
            echo $mail->ErrorInfo;
        }
    }

    private static function sendMailSmtp2go() {
        $mail = new \PHPMailer();

        $host = 'mail.smtp2go.com';
        $userName = 'default_user';
        $password = 'login123';

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $userName;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('eglife@support.com', 'eglife');
        $mail->addAddress('nguyenhoc89@gmail.com', 'nguyenhoc89');
        $mail->Subject = 'Try somethings';
        $mail->Body    = 'This is the email from smtp2go';
        $result = $mail->send();

        if(!$result) {
            echo $mail->ErrorInfo;
        }
    }
}