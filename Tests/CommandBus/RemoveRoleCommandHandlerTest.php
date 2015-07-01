<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\RemoveRoleCommand;
use Spraed\CommandUserBundle\CommandBus\RemoveRoleCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class RemoveRoleCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();

        $commandHandler = new RemoveRoleCommandHandler();
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        $command = new RemoveRoleCommand($user, 'TO_REMOVE');

        return $command;
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('removeRole')
            ->with('ROLE_TO_REMOVE');

        return $user;
    }


}