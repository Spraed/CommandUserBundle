<?php

namespace Spraed\CommandUserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Spraed\CommandUserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class DoctrineUserRepository extends EntityRepository implements UserRepository, UserProviderInterface
{

    /**
     * @param string $username
     *
     * @return User|null
     * @throws UsernameNotFoundException
     */
    public function findUserByUsername($username)
    {
        return $this->loadUserByUsername($username);
    }


    /**
     * @return User[]
     */
    public function findAllUsers()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb
            ->select('user')
            ->from('AppBundle:User\User', 'user')
            ->orderBy('user.username')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->_em->persist($user);
    }

    /**
     * @param string $username
     *
     * @return User|null
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $query = $this
            ->createQueryBuilder('user')
            ->where('user.username = :username OR user.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active AppBundle:User\User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
} 