<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

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
     */
    public $fullName;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @param string $username
     * @param string $fullName
     * @param string $email
     */
    public function __construct($username, $fullName, $email)
    {
        $this->username = $username;
        $this->fullName = $fullName;
        $this->email = $email;
    }
}