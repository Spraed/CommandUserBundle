<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\AddRoleCommand;
use Spraed\CommandUserBundle\CommandBus\AddRoleCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class AddRoleCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();

        $commandHandler = new AddRoleCommandHandler();
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        $command = new AddRoleCommand($user, 'TO_ADD');

        return $command;
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('addRole')
            ->with('ROLE_TO_ADD');

        return $user;
    }


}