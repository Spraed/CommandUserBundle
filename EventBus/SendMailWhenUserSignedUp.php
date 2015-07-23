<?php

namespace Spraed\CommandUserBundle\EventBus;

use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class SendMailWhenUserSignedUp
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
     * @param UserSignedUpEvent $event
     *
     * @return void
     */
    public function notify(UserSignedUpEvent $event)
    {
        // todo: translator is missing for subject
        $message = $this->mailer->createMessage()
            ->setSubject('user.mail.signup.subject')
            ->setFrom('info@spraed-it.de')
            ->setTo($event->email)
            ->setBody($this->templating->render(
                'user/user_registration_mail.txt.twig',
                [
                    'username' => $event->username,
                    'password' => $event->password,
                ]
            ));

        $this->mailer->send($message);
    }

}
