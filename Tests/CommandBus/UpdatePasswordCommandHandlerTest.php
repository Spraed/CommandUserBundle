<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\UpdatePasswordCommand;
use Spraed\CommandUserBundle\CommandBus\UpdatePasswordCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UpdatePasswordCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();
        $encoderFactory = $this->getEncoderFactoryMock();

        $commandHandler = new UpdatePasswordCommandHandler($encoderFactory);
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        $command = new UpdatePasswordCommand($user);
        $command->oldPassword = 'oldpassword';
        $command->newPassword = 'newpassword';

        return $command;
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
            ->with('newpassword', 'usersalt')
            ->will($this->returnValue('encryptedpassword'));

        return $encoder;
    }

}