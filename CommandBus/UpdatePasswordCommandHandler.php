<?php

namespace Spraed\CommandUserBundle\CommandBus;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UpdatePasswordCommandHandler
{
    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * @param EncoderFactory $encoderFactory
     */
    public function __construct(EncoderFactory $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param UpdatePasswordCommand $command
     */
    public function handle(UpdatePasswordCommand $command)
    {
        $user = $command->user;

        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword($command->newPassword, $user->getSalt());

        $user->updatePassword($password);
    }
} 