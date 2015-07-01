<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\UpdateUserProfileCommand;
use Spraed\CommandUserBundle\CommandBus\UpdateUserProfileCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UpdateUserProfileCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();

        $commandHandler = new UpdateUserProfileCommandHandler();
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        $command = new UpdateUserProfileCommand($user);
        $command->username = 'Updatedusername';
        $command->email = 'name@test.com';

        return $command;
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('updateProfile')
            ->with('Updatedusername', 'name@test.com');

        return $user;
    }


}