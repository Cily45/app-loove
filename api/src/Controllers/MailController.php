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
            $link = $_ENV['URL'] . "/confirmation/" . $token;
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
            $link = $_ENV['URL'] . "/reinitialisation-mot-de-passe/" . $token;
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

    function sendContact(): bool
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $mailUser = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null;
        $firstname = isset($data['firstname']) ? cleanString($data['firstname']) : null;
        $lastname = isset($data['lastname']) ? cleanString($data['lastname']) : null;
        $raison = isset($data['select']) ? cleanString($data['select']) : null;
        $text = isset($data['textarea']) ? cleanString($data['textarea']) : null;

        $mail = new PHPMailer(true);
        $from = 'no-reply@example.com';
        $fromName = $mailUser;

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pawfect.match.system@gmail.com';
            $mail->Password = 'aoru ffue fdej gqvn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom($from, $fromName);
            $mail->addAddress('pawfect.match.system@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = $raison;

            $mail->Body = "<html>
        <head><title>Demande de contact</title></head>
        <body>
            <p><strong>Prénom :</strong> $firstname</p>
            <p><strong>Nom :</strong> $lastname</p>
            <p><strong>Email :</strong> $mailUser</p>
            <p><strong>Raison :</strong> $raison</p>
            <p><strong>Message :</strong><br>" . nl2br($text) . "</p>
        </body>
        </html>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
            return false;
        }
    }

    function sendMatch(string $userName, string $userPhoto, int $userId, string $mailUser): bool
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
            $mail->Subject = 'Vous avez un nouveau pawfect match!';
            $body = file_get_contents(__DIR__ . "/../mail-template/matchNotification.html");
            $link = $_ENV['URL'] . "/messagerie/discussion/" . $userId;
            $body = str_replace('{{ chatLink }}', $link, $body);
            $body = str_replace('{{ username }}', $userName, $body);
            $mail->addEmbeddedImage(__DIR__ . "/../../uploads/photo-user/" . $userPhoto, 'user-photo');
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
            return false;
        }
    }

    function sendMessage(string $userName, string $userPhoto, int $userId, string $message, string $mailUser): bool
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
            $mail->Subject = 'Vous avez un nouveau message';
            $body = file_get_contents(__DIR__ . "/../mail-template/messageNotification.html");
            $link = $_ENV['URL'] . "/messagerie/discussion/" . $userId;
            $body = str_replace('{{ chatLink }}', $link, $body);
            $body = str_replace('{{ senderName }}', $userName, $body);
            $body = str_replace('{{ messageSnippet }}', $message, $body);
            $mail->addEmbeddedImage(__DIR__ . "/../../uploads/photo-user/" . $userPhoto, 'user-photo');
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
            return false;
        }
    }
}