<?php

namespace Spraed\CommandUserBundle\CommandBus;

use SimpleBus\Message\Recorder\RecordsMessages;
use Spraed\CommandUserBundle\Entity\User;
use Spraed\CommandUserBundle\EventBus\UserSignedUpEvent;
use Spraed\CommandUserBundle\Repository\UserRepository;
use Spraed\CommandUserBundle\Service\PasswordService;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class SignUpUserCommandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

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
     * @param UserRepository  $userRepository
     * @param PasswordService $passwordService
     * @param EncoderFactory  $encoderFactory
     * @param RecordsMessages $eventRecorder
     */
    public function __construct(UserRepository $userRepository, PasswordService $passwordService, EncoderFactory $encoderFactory, RecordsMessages $eventRecorder)
    {
        $this->userRepository = $userRepository;
        $this->passwordService = $passwordService;
        $this->encoderFactory = $encoderFactory;
        $this->eventRecorder = $eventRecorder;
    }

    /**
     * @param SignUpUserCommand $command
     */
    public function handle(SignUpUserCommand $command)
    {
        $user = new User($command->username, $command->email);

        $password = $this->passwordService->generatePassword();
        $encoder = $this->encoderFactory->getEncoder($user);
        $encryptedPassword = $encoder->encodePassword($password, $user->getSalt());

        $user->updatePassword($encryptedPassword);
        $this->userRepository->addUser($user);

        // send mail to new user
        $event = new UserSignedUpEvent($command->username, $command->email, $password);
        $this->eventRecorder->record($event);
    }
}