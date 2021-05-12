<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

$response = '';

// Basic example of some form validation
$valid = true;

// Message have to be filled
if (empty($_POST['mensaje'])) {
    $valid = false;
    $response = json_encode(['status' => 'error', 'message' => 'Usted tiene que escribir alguna mensaje a nosotros']);
}

// One of phone or mail have to be filled
if (empty($_POST["correo"]) && empty($_POST["telefono"])) {
    $valid = false;
    $response = json_encode(['status' => 'error', 'message' => 'Usted tiene que darnos su correo electrónico o número de teléfono']);
} else {
    if (!empty($_POST["correo"])) {
        $email = trim($_POST["correo"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $response = json_encode(['status' => 'error', 'message' => 'Usted tiene que darnos un válido correo electrónico']);
        }
    }
}


if ($valid) {
//Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {

        // Server settings for some normal SMTP server
        /*
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'user@example.com';                     //SMTP username
        $mail->Password   = 'secret';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
        $mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        //Attachments
        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        */

        // Settings for Gmail
        $mail = new PHPMailer(); // create a new object

        // Below lines are SMTP specific
        // Start SMTP
        $mail->IsSMTP(); // enable SMTP
        // For testing ans seeing the smtp mail server messages
        //$mail->SMTPDebug = SMTP::DEBUG_CLIENT; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->Username = "mail@gmail.com";
        $mail->Password = "";
        // End SMTP

        // If you're using the server's mail settings through the mail() function
        /*
        $mail->IsMail(); // enable usage of the normal mail() function
        */

        // Sender's e-mail
        $mail->SetFrom("andreyaraque@gmail.com");
        $mail->Subject = "Contact Form - " . $_POST['correo'];
        $mail->Body = "Nombre: " . $_POST['nombre'] . "\nCorreo: " . $_POST['correo'] . "\nTelefono: " . $_POST['telefono'] . "\nMensaje: " . $_POST['mensaje'];

        // recipient's e-mail
        $mail->AddAddress("mkoula@gmail.com");


        $response = json_encode(['status' => 'sent']);
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $response = json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
    }
}

// ajax-json response
header('Content-type: application/json; charset=utf-8');
echo $response;

// - if there won't be any ajax-json response - redirect back
// header("location:index.html");
