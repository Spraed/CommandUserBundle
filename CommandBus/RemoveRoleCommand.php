<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Spraed\CommandUserBundle\Entity\User;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class RemoveRoleCommand
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $role;

    /**
     * @param User   $user
     * @param string $role
     */
    public function __construct(User $user, $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

}