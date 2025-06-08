<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends BaseController
{
    function sendConfirm(string $token, string $mailUser): bool
    {
        $mail = new PHPMailer(true);
        $from = 'no-reply@example.com';
        $fromName = 'Pawfect-match';

        try {
            // Configuration serveur SMTP (exemple Gmail)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pawfect.match.system@gmail.com';     // ton email SMTP
            $mail->Password = 'aoru ffue fdej gqvn';         // mot de passe SMTP ou App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Expéditeur et destinataire
            $mail->setFrom($from, $fromName);
            $mail->addAddress($mailUser);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation d\'inscription';
            $body = file_get_contents(__DIR__ . "/../mail-template/emailConfirmation.html");
            $link = "http://82.65.121.169:4200/confirmation/" . $token;
            $body = str_replace('{{verification_link}}', $link, $body);
            $mail->addEmbeddedImage(__DIR__ . "/../../uploads/logo-entier.png", 'logoCID');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
            return false;
        }
    }

    function sendReset(string $token, string $mailUser): bool
    {
        $mail = new PHPMailer(true);
        $from = 'no-reply@example.com';
        $fromName = 'Pawfect-match';

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pawfect.match.system@gmail.com';
            $mail->Password = 'aoru ffue fdej gqvn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom($from, $fromName);
            $mail->addAddress($mailUser);

            $mail->isHTML(true);
            $mail->Subject = 'Reinitialisation de mot de passe';
            $body = file_get_contents(__DIR__ . "/../mail-template/resetPass.html");
            $link = "http://82.65.121.169:4200/reinitialisation-mot-de-passe/" . $token;
            $body = str_replace('{{verification_link}}', $link, $body);
            $mail->addEmbeddedImage(__DIR__ . "/../../uploads/logo-entier.png", 'logoCID');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
            return false;
        }
    }
}