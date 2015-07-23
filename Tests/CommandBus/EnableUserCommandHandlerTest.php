<?php

namespace Spraed\CommandUserBundle\Tests\CommandBus;

use Spraed\CommandUserBundle\CommandBus\EnableUserCommand;
use Spraed\CommandUserBundle\CommandBus\EnableUserCommandHandler;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class EnableUserCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $command = $this->getCommandMock();

        $commandHandler = new EnableUserCommandHandler();
        $commandHandler->handle($command);
    }

    private function getCommandMock()
    {
        $user = $this->getUserMock();

        $command = new EnableUserCommand($user);

        return $command;
    }

    private function getUserMock()
    {
        $user = $this->getMockBuilder('Spraed\CommandUserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->once())
            ->method('enableUser');

        return $user;
    }


}