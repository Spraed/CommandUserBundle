<?php

namespace Spraed\CommandUserBundle\Command;

use Spraed\CommandUserBundle\CommandBus\SignUpUserCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author stedekay <stedekay@posteo.de>
 */
class SignUpUserConsoleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('spraed:user:signup')
            ->setDescription('Sign up a new User')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('email', InputArgument::REQUIRED, 'E-Mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');

        $command = new SignUpUserCommand($username, $email);
        $this->getContainer()->get('command_bus')->handle($command);

        $output->writeln(sprintf('User <comment>%s</comment> signed up.', $username));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $username = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a username: ',
                function ($username) {
                    if (empty($username)) {
                        throw new \Exception('Username can not be empty');
                    }
                    return $username;
                }
            );
            $input->setArgument('username', $username);
        }

        if (!$input->getArgument('email')) {
            $email = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose an email: ',
                function ($email) {
                    if (empty($email)) {
                        throw new \Exception('Email can not be empty');
                    }
                    return $email;
                }
            );
            $input->setArgument('email', $email);
        }
    }
} 