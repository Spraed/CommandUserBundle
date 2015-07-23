<?php

namespace Spraed\CommandUserBundle\Tests\Entity;

use Spraed\CommandUserBundle\Entity\User;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->user = new User('username', 'user@test.com');
        $this->user->updatePassword('password');
        $this->user->addRole('ROLE_ADMIN');
    }

    public function testDisableUser()
    {
        $this->user->disableUser();

        $this->assertFalse($this->user->isEnabled());
    }

    public function testEnableUser()
    {
        $this->user->enableUser();

        $this->assertTrue($this->user->isEnabled());
    }

    public function testUpdateProfile()
    {
        $this->user->updateProfile('Newusername', 'newuser@test.com');

        $this->assertEquals('Newusername', $this->user->getUsername());
        $this->assertEquals('newuser@test.com', $this->user->getEmail());
    }

    public function testUpdatePassword()
    {
        $this->user->updatePassword('newpassword');

        $this->assertEquals('newpassword', $this->user->getPassword());
    }

    public function testAddRole()
    {
        $this->user->addRole('ROLE_TEST');

        $this->assertCount(3, $this->user->getRoles());
        $this->assertContains('ROLE_TEST', $this->user->getRoles());
    }

    public function testRemoveRole()
    {
        $this->user->removeRole('ROLE_ADMIN');

        $this->assertCount(1, $this->user->getRoles());
        $this->assertNotContains('ROLE_ADMIN', $this->user->getRoles());
    }
}