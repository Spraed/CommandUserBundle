<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\SignUpUserCommand;
use Spraed\CommandUserBundle\CommandBus\SignUpUserCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class SignUpUserCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = new SignUpUserCommand('newbie', 'newbie@test.com');

        $userRepository = $this->getUserRepositoryMock();
        $passwordService = $this->getPasswordServiceMock();
        $encoderFactory = $this->getEncoderFactoryMock();
        $eventRecorder = $this->getRecordsMessagesMock();

        $commandHandler = new SignUpUserCommandHandler($userRepository, $passwordService, $encoderFactory, $eventRecorder);
        $commandHandler->handle($command);
    }

    private function getUserRepositoryMock()
    {
        $repository = $this->getMockBuilder('Spraed\CommandUserBundle\Repository\DoctrineUserRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $repository->expects($this->once())
            ->method('addUser');

        return $repository;
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
            ->with('generatedpassword');

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