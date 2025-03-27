<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Twig\Environment as TwigEnvironment;
use Psr\Log\LoggerInterface;


class MailService
{
    private MailerInterface $mailer;
    private TwigEnvironment $twig;
    private string $appUrl;
    private LoggerInterface $logger;
    

    public function __construct(MailerInterface $mailer, TwigEnvironment $twig, string $appUrl,  LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->appUrl = $appUrl;
        $this->logger = $logger;
        
}
    

    public function sendResetPasswordEmail(string $to, string $username, string $token): void
    {
        // construis ton lien avec ton APP_URL
        $resetLink = sprintf('%s/reset-password?token=%s', $this->appUrl, $token);

        $htmlContent = $this->twig->render('emails/reset_password.html.twig', [
            'username' => $username,
            'resetLink' => $resetLink
        ]);

        $email = (new Email())
            ->from(new Address('ton.email@gmail.com', 'Gefor Emargement'))
            ->to($to)
            ->subject('Réinitialisation de votre mot de passe')
            ->html($htmlContent)
            ->text(sprintf(
                "Bonjour %s,\n\nCliquez sur ce lien pour réinitialiser votre mot de passe : %s\n\nCe lien expire dans 1 heure.",
                $username,
                $resetLink
            ));

        try {
            $this->mailer->send($email);
        } catch (\Throwable $exception) {
            dump($exception->getMessage()); // Optionnel pour voir en DEV
            $this->logger->error('Erreur Mailer : ' . $exception->getMessage());
        }
}

}

