<?php

namespace Spraed\CommandUserBundle\EventBus;

use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * @author stedekay <stedekay@posteo.de>
 */
class SendMailWhenPasswordResetted
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @param \Swift_Mailer $mailer
     * @param TwigEngine    $templating
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param PasswordResettedEvent $message
     */
    public function notify(PasswordResettedEvent $message)
    {
        $user = $message->user;

        // todo: translator is missing for subject
        $message = $this->mailer->createMessage()
            ->setSubject('user.mail.reset.subject')
            ->setFrom('info@spraed-it.de')
            ->setTo($user->getEmail())
            ->setBody($this->templating->render(
                'user/user_password_reset_mail.txt.twig',
                [
                    'password' => $message->password,
                ]
            ));

        $this->mailer->send($message);
    }

} 