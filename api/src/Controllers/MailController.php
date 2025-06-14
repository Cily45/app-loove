<?php

namespace App\Controllers;

use App\Models\UserModel;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailController extends BaseController
{
    private const SMTP_HOST = 'smtp.gmail.com';
    private const SMTP_USER = 'pawfect.match.system@gmail.com';
    private const SMTP_PASS = 'aoru ffue fdej gqvn';
    private const SMTP_PORT = 587;
    private const SMTP_FROM = 'no-reply@example.com';
    private const SMTP_NAME = 'Pawfect-match';


    private function getMailer(string $toEmail, string $fromName = self::SMTP_NAME): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = self::SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = self::SMTP_USER;
        $mail->Password = self::SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = self::SMTP_PORT;

        $mail->setFrom(self::SMTP_FROM, $fromName);
        $mail->addAddress($toEmail);
        $mail->isHTML(true);

        return $mail;
    }

    public function sendConfirm(string $token, string $mailUser): bool
    {
        try {
            $mail = $this->getMailer($mailUser);
            $mail->Subject = 'Confirmation d\'inscription';

            $body = file_get_contents(__DIR__ . '/../mail-template/emailConfirmation.html');
            $link = $_ENV['URL'] . '/confirmation/' . $token;
            $body = str_replace('{{verification_link}}', $link, $body);
            $mail->addEmbeddedImage(__DIR__ . '/../../uploads/logo-entier.png', 'logoCID');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail (confirmation) : {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendReset(string $token, string $mailUser): bool
    {
        try {
            $mail = $this->getMailer($mailUser);
            $mail->Subject = 'Réinitialisation de mot de passe';

            $body = file_get_contents(__DIR__ . '/../mail-template/resetPass.html');
            $link = $_ENV['URL'] . '/reinitialisation-mot-de-passe/' . $token;
            $body = str_replace('{{verification_link}}', $link, $body);
            $mail->addEmbeddedImage(__DIR__ . '/../../uploads/logo-entier.png', 'logoCID');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail (reset) : {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendContact(): bool
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $mailUser = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $firstname = cleanString($data['firstname'] ?? '');
        $lastname = cleanString($data['lastname'] ?? '');
        $raison = cleanString($data['select'] ?? '');
        $text = nl2br(cleanString($data['textarea'] ?? ''));

        if (!$mailUser) return false;

        try {
            $mail = $this->getMailer(self::SMTP_USER, $mailUser);
            $mail->Subject = $raison;

            $mail->Body = "
                <html><body>
                    <p><strong>Prénom :</strong> $firstname</p>
                    <p><strong>Nom :</strong> $lastname</p>
                    <p><strong>Email :</strong> $mailUser</p>
                    <p><strong>Raison :</strong> $raison</p>
                    <p><strong>Message :</strong><br>$text</p>
                </body></html>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail (contact) : {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendMatch(string $userName, string $userPhoto, int $userId, string $mailUser): bool
    {
        try {
            $mail = $this->getMailer($mailUser);
            $mail->Subject = 'Vous avez un nouveau pawfect match!';

            $body = file_get_contents(__DIR__ . '/../mail-template/matchNotification.html');
            $link = $_ENV['URL'] . '/messagerie/discussion/' . $userId;
            $body = str_replace(['{{ chatLink }}', '{{ username }}'], [$link, $userName], $body);
            $mail->addEmbeddedImage(__DIR__ . "/../../uploads/photo-user/$userPhoto", 'user-photo');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail (match) : {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendMessage(string $userName, string $userPhoto, int $userId, string $message, string $mailUser): bool
    {
        try {
            $mail = $this->getMailer($mailUser);
            $mail->Subject = 'Vous avez un nouveau message';

            $body = file_get_contents(__DIR__ . '/../mail-template/messageNotification.html');
            $link = $_ENV['URL'] . '/messagerie/discussion/' . $userId;
            $body = str_replace(
                ['{{ chatLink }}', '{{ senderName }}', '{{ messageSnippet }}'],
                [$link, $userName, $message],
                $body
            );
            $mail->addEmbeddedImage(__DIR__ . "/../../uploads/photo-user/$userPhoto", 'user-photo');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail (message) : {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendWarning(): bool
    {
        if(!$this->isAdmin()){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $reasonMessage = ["Comportement inapproprié" => "
    <p>Nous avons reçu un signalement concernant un comportement jugé inapproprié sur la plateforme Pawfect Match.</p>
    <p>Merci de respecter les autres utilisateurs et de suivre notre charte d'utilisation. En cas de récidive, des mesures plus strictes pourraient être prises.</p>
    <p>En cas de récidive, votre compte pourrait être suspendu ou supprimé.</p>",
            "Harcèlement" => "
    <p>Nous avons reçu un ou plusieurs signalements indiquant que votre comportement pourrait être assimilé à du harcèlement.</p>
    <p>Ce type de comportement est strictement interdit sur notre plateforme. Merci de cesser immédiatement toute interaction inappropriée.</p>
    <p>En cas de récidive, votre compte pourrait être suspendu ou supprimé.</p>",
            "Faux profil" => "
    <p>Nous avons détecté ou reçu des signalements indiquant que votre profil pourrait être faux ou trompeur.</p>
    <p>Merci de vérifier que vos informations sont authentiques et conformes à la réalité.</p>
    <p>Sans mise à jour de votre part, nous serons contraints de supprimer votre compte.</p>",
            "Spam" => "
    <p>Nous avons détecté des comportements assimilables à du spam (envoi massif ou non sollicité de messages).</p>
    <p>Merci d’arrêter ces activités immédiatement. Le spam perturbe l’expérience utilisateur et enfreint nos conditions d'utilisation.</p>
    <p>En cas de récidive, votre compte pourrait être suspendu ou supprimé.</p>",
            ] ;
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $reason = $data['reason'] ?? null;
            $idUser = (int) $data['id'] ?? null;

            if(!$reason || !$idUser){
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $userController = new UserModel();
            $user = $userController->get($idUser);
            $mailUser = $user["email"];
            $mail = $this->getMailer($mailUser);
            $mail->Subject = 'Vous avez un nouveau message';

            $body = file_get_contents(__DIR__ . '/../mail-template/messageNotification.html');
            $body = str_replace(
                ['{{ reason }}', '{{contact_link}}', '{{ message }}'],
                [$reason, $_ENV['URL'] . '/contact/', $reasonMessage[$reason]],
                $body
            );
            $mail->addEmbeddedImage(__DIR__ . '/../../uploads/logo-entier.png', 'logoCID');
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi de mail (message) : {$mail->ErrorInfo}");
            return false;
        }
    }
}
