<?php

namespace Spraed\CommandUserBundle\Tests\EventBus;

use Spraed\CommandUserBundle\EventBus\SendMailWhenUserSignedUp;
use Spraed\CommandUserBundle\EventBus\UserSignedUpEvent;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class SendMailWhenUserSignedUpTest extends \PHPUnit_Framework_TestCase
{
    public function testEventNotify()
    {
        $event = new UserSignedUpEvent('user', 'user@test.com', 'password');

        $mailer = $this->getMailerMock();
        $templating = $this->getTemplatingMock();

        $eventHandler = new SendMailWhenUserSignedUp($mailer, $templating);
        $eventHandler->notify($event);
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
            ->with('user.mail.signup.subject')
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
            ->with('user/user_registration_mail.txt.twig', [
                'username' => 'user',
                'password' => 'password',
            ]);

        return $eventRecorder;
    }
} 