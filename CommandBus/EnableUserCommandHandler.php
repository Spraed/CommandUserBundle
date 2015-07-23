<?php

namespace Spraed\CommandUserBundle\CommandBus;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class EnableUserCommandHandler
{

    /**
     * @param EnableUserCommand $message
     */
    public function handle(EnableUserCommand $message)
    {
        $user = $message->user;
        $user->enableUser();
    }

} 