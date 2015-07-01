<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Spraed\CommandUserBundle\Entity\User;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UpdatePasswordCommand
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     * @Assert\NotBlank
     * @SecurityAssert\UserPassword(message = "user.error.old_password")
     */
    public $oldPassword;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $newPassword;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback
     */
    public function comparePasswords(ExecutionContextInterface $context)
    {
        if ($this->oldPassword === $this->newPassword) {
            $context->buildViolation('user.error.same_password')
                ->atPath('newPassword')
                ->addViolation();
        }
    }

}