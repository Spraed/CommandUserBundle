<?php

namespace Spraed\CommandUserBundle\CommandBus;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class DisableUserCommandHandler
{

    /**
     * @param DisableUserCommand $message
     */
    public function handle(DisableUserCommand $message)
    {
        $user = $message->user;
        $user->disableUser();
    }

} 