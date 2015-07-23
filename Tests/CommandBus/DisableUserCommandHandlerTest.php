<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\DisableUserCommand;
use Spraed\CommandUserBundle\CommandBus\DisableUserCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class DisableUserCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();

        $commandHandler = new DisableUserCommandHandler();
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        $command = new DisableUserCommand($user);

        return $command;
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('disableUser');

        return $user;
    }


}