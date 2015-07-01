<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class SignUpUserCommand
{
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
     * @param string $username
     * @param string $email
     */
    public function __construct($username, $email)
    {
        $this->username = $username;
        $this->email = $email;
    }
}