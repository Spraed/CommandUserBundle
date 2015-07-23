<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Spraed\CommandUserBundle\Entity\User;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UpdateUserProfileCommand
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $username;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->username = $user->getUsername();
        $this->email = $user->getEmail();
    }

}