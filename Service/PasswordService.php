<?php

namespace Spraed\CommandUserBundle\Service;

/**
 * @author Stefan Blanke <stedekay@posteo.de>
 */
class PasswordService
{

    /**
     * @return string
     */
    public function generatePassword()
    {
        $length = 12;
        $chars = 'bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ23456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}