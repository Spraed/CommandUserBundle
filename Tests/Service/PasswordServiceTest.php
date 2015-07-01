<?php

namespace Spraed\CommandUserBundle\Tests\Service;

use Spraed\CommandUserBundle\Service\PasswordService;

/**
 * @author stedekay <stedekay@posteo.de>
 */
class PasswordServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratePassword()
    {
        $passwordService = new PasswordService();
        $password = $passwordService->generatePassword();

        $this->assertEquals(12, strlen($password));
        $this->assertFalse((bool) preg_match('#[^bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ23456789]#', $password));

        $password2 = $passwordService->generatePassword();
        $this->assertNotEquals($password, $password2);
    }
} 