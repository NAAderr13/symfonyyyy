<?php
namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmpruntNotification
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNotification(string $email, string $bookTitle, \DateTimeInterface $returnDate): void
    {
        $message = (new Email())
            ->from('no-reply@bibliotheque.com')
            ->to($email)
            ->subject('Rappel de Retour de Livre')
            ->text(
                sprintf(
                    'Bonjour,\n\nVous avez emprunté le livre "%s". La date de retour prévue est le %s.\n\nMerci de bien vouloir retourner le livre à temps.',
                    $bookTitle,
                    $returnDate->format('d/m/Y')
                )
            );

        $this->mailer->send($message);
    }
}
