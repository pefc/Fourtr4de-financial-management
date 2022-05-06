<?php

declare(strict_types=1);

namespace App\Handler\Mailer;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Container\ContainerInterface;

class MailHandler
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    private $container;

    public function __construct(MailerInterface $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function sendEmail(array $data): void
    {
        $email = (new Email())
            ->from($this->container->get('settings')['smtp']['from'])
            ->to($data['to'])
            ->priority(Email::PRIORITY_HIGH)
            ->subject($data['subject'])
            ->text(strip_tags($data['html']))
            ->html($data['html']);

        $this->mailer->send($email);
    }
}