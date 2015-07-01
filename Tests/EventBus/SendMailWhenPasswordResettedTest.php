<?php

namespace Spraed\CommandUserBundle\Tests\EventBus;

use Spraed\CommandUserBundle\EventBus\PasswordResettedEvent;
use Spraed\CommandUserBundle\EventBus\SendMailWhenPasswordResetted;


/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class SendMailWhenPasswordResettedTest extends \PHPUnit_Framework_TestCase
{
    public function testEventNotify()
    {
        $user = $this->getUserMock();
        $event = new PasswordResettedEvent($user, 'resettedpassword');

        $mailer = $this->getMailerMock();
        $templating = $this->getTemplatingMock();

        $eventHandler = new SendMailWhenPasswordResetted($mailer, $templating);
        $eventHandler->notify($event);
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue('user@test.com'));

        return $user;
    }

    private function getMailerMock()
    {
        $mailer = $this->getMockBuilder('\Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();

        $mailer->expects($this->once())
            ->method('createMessage')
            ->will($this->returnValue($this->getMessageMock()));

        $mailer->expects($this->once())
            ->method('send');

        return $mailer;
    }

    private function getMessageMock()
    {
        $message = $this->getMockBuilder('\Swift_Mime_SimpleMessage')
            ->disableOriginalConstructor()
            ->getMock();

        $message->expects($this->once())
            ->method('setSubject')
            ->with('user.mail.reset.subject')
            ->will($this->returnValue($message));

        $message->expects($this->once())
            ->method('setFrom')
            ->with('info@spraed-it.de')
            ->will($this->returnValue($message));

        $message->expects($this->once())
            ->method('setTo')
            ->with('user@test.com')
            ->will($this->returnValue($message));

        $message->expects($this->once())
            ->method('setBody')
            ->will($this->returnValue($message));

        return $message;
    }

    private function getTemplatingMock()
    {
        $eventRecorder = $this->getMockBuilder('Symfony\Bundle\TwigBundle\TwigEngine')
            ->disableOriginalConstructor()
            ->getMock();

        $eventRecorder->expects($this->once())
            ->method('render')
            ->with('user/user_password_reset_mail.txt.twig', [
                'password' => 'resettedpassword'
            ]);

        return $eventRecorder;
    }
} 