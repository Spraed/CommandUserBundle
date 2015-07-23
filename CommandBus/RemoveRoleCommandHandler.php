<?php

namespace Spraed\CommandUserBundle\CommandBus;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class RemoveRoleCommandHandler
{

    /**
     * @param RemoveRoleCommand $message
     */
    public function handle(RemoveRoleCommand $message)
    {
        $role = 'ROLE_' . $message->role;

        $user = $message->user;
        $user->removeRole($role);
    }

} 