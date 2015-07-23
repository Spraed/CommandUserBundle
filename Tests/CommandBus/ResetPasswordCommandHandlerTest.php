<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\ResetPasswordCommand;
use Spraed\CommandUserBundle\CommandBus\ResetPasswordCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class ResetPasswordCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();
        $passwordService = $this->getPasswordServiceMock();
        $encoderFactory = $this->getEncoderFactoryMock();
        $eventRecorder = $this->getRecordsMessagesMock();

        $commandHandler = new ResetPasswordCommandHandler($passwordService, $encoderFactory, $eventRecorder);
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        return new ResetPasswordCommand($user);
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('updatePassword')
            ->with('encryptedpassword');

        $user->expects($this->once())
            ->method('getSalt')
            ->will($this->returnValue('usersalt'));

        return $user;
    }

    private function getPasswordServiceMock()
    {
        $passwordService = $this->getMockBuilder('Spraed\CommandUserBundle\Service\PasswordService')
            ->getMock();

        $passwordService->expects($this->once())
            ->method('generatePassword')
            ->will($this->returnValue('generatedpassword'));

        return $passwordService;
    }

    private function getEncoderFactoryMock()
    {
        $encoderFactory = $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\EncoderFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $encoderFactory->expects($this->once())
            ->method('getEncoder')
            ->will($this->returnValue($this->getEncoderMock()));

        return $encoderFactory;
    }

    private function getEncoderMock()
    {
        $encoder = $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with('generatedpassword', 'usersalt')
            ->will($this->returnValue('encryptedpassword'));

        return $encoder;
    }

    private function getRecordsMessagesMock()
    {
        $eventRecorder = $this->getMockBuilder('SimpleBus\Message\Recorder\RecordsMessages')
            ->getMock();

        $eventRecorder->expects($this->once())
            ->method('record');

        return $eventRecorder;
    }

}