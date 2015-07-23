<?php

namespace Spraed\CommandUserBundle\EventBus;

use Spraed\CommandUserBundle\Entity\User;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class PasswordResettedEvent
{
    public $user;
    public $password;

    /**
     * @param User   $user
     * @param string $password
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

}