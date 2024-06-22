<?php

require_once('phpmailer/PHPMailer.php');
require_once('phpmailer/SMTP.php');
require_once('phpmailer/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0; // Set to 0 for production
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'musictracks97@gmail.com'; // SMTP username
    $mail->Password = 'awevrzfzhuwytruu'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable SSL encryption
    $mail->Port = 465;

    $message = "";
    $status = "false";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = isset($_POST['form_name']) ? trim($_POST['form_name']) : '';
        $email = isset($_POST['form_email']) ? trim($_POST['form_email']) : '';
        $subject = isset($_POST['form_subject']) ? trim($_POST['form_subject']) : '';
        $phone = isset($_POST['form_phone']) ? trim($_POST['form_phone']) : '';
        $messageContent = isset($_POST['form_message']) ? trim($_POST['form_message']) : '';
        $botcheck = isset($_POST['form_botcheck']) ? trim($_POST['form_botcheck']) : '';

        if ($name !== '' && $email !== '' && $subject !== '') {
            if ($botcheck == '') {
                $toemail = 'spam.thememascot@gmail.com'; // Your Email Address
                $toname = 'ThemeMascot'; // Your Name

                $mail->setFrom($email, $name);
                $mail->addReplyTo($email, $name);
                $mail->addAddress($toemail, $toname);
                $mail->Subject = $subject;

                $body = "Name: $name<br>Email: $email<br>Phone: $phone<br>Message: $messageContent<br>";
                $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';
                $body .= $referrer;

                $mail->msgHTML($body);
                $mail->send();

                $message = 'We have <strong>successfully</strong> received your message and will get back to you as soon as possible.';
                $status = "true";
            } else {
                $message = 'Bot <strong>detected</strong>. Please clean yourself, Botster!';
                $status = "false";
            }
        } else {
            $message = 'Please <strong>fill up</strong> all the fields and try again.';
            $status = "false";
        }
    } else {
        $message = 'An <strong>unexpected error</strong> occurred. Please try again later.';
        $status = "false";
    }

    $status_array = array('message' => $message, 'status' => $status);
    echo json_encode($status_array);

} catch (Exception $e) {
    $status_array = array('message' => 'Mailer Error: ' . $mail->ErrorInfo, 'status' => 'false');
    echo json_encode($status_array);
}
?>
