<?php

namespace Spraed\CommandUserBundle\Repository;

use Spraed\CommandUserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
interface UserRepository
{
    /**
     * @param string $username
     *
     * @return User
     * @throws UsernameNotFoundException
     */
    public function findUserByUsername($username);

    /**
     * @return User[]
     */
    public function findAllUsers();

    /**
     * @param User $user
     *
     * @return void
     */
    public function addUser(User $user);
} 