<?php

namespace Spraed\CommandUserBundle\CommandBus;

use SimpleBus\Message\Recorder\RecordsMessages;
use Spraed\CommandUserBundle\EventBus\PasswordResettedEvent;
use Spraed\CommandUserBundle\Service\PasswordService;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class ResetPasswordCommandHandler
{

    /**
     * @var PasswordService
     */
    private $passwordService;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * @var RecordsMessages
     */
    private $eventRecorder;

    /**
     * @param PasswordService $passwordService
     * @param EncoderFactory  $encoderFactory
     * @param RecordsMessages $eventRecorder
     */
    public function __construct(PasswordService $passwordService, EncoderFactory $encoderFactory, RecordsMessages $eventRecorder)
    {
        $this->eventRecorder = $eventRecorder;
        $this->encoderFactory = $encoderFactory;
        $this->passwordService = $passwordService;
    }


    /**
     * @param ResetPasswordCommand $message
     */
    public function handle(ResetPasswordCommand $message)
    {
        $user = $message->user;

        $password = $this->passwordService->generatePassword();
        $encoder = $this->encoderFactory->getEncoder($user);
        $encryptedPassword = $encoder->encodePassword($password, $user->getSalt());

        $user->updatePassword($encryptedPassword);

        // send mail to user
        $event = new PasswordResettedEvent($message->user, $password);
        $this->eventRecorder->record($event);
    }

} 