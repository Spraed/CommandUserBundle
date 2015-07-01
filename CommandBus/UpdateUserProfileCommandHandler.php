<?php

namespace Spraed\CommandUserBundle\CommandBus;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UpdateUserProfileCommandHandler
{

    /**
     * @param UpdateUserProfileCommand $command
     */
    public function handle(UpdateUserProfileCommand $command)
    {
        $user = $command->user;
        $user->updateProfile($command->username, $command->email);
    }
} 