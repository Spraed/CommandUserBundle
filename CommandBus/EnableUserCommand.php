<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Spraed\CommandUserBundle\Entity\User;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class EnableUserCommand
{
    /**
     * @var User
     */
    public $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}