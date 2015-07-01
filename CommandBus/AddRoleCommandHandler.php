<?php

namespace Spraed\CommandUserBundle\CommandBus;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class AddRoleCommandHandler
{

    /**
     * @param AddRoleCommand $message
     */
    public function handle(AddRoleCommand $message)
    {
        $role = 'ROLE_' . $message->role;

        $user = $message->user;
        $user->addRole($role);
    }

} 