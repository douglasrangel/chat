<?php

namespace Simplon\Helper;

class SecurityUtil
{
    const TOKEN_ALL_CASE_LETTERS_NUMBERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const TOKEN_UPPERCASE_LETTERS_NUMBERS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @param string $password
     * @param int $algo
     * @param array $options
     *
     * @return string|null
     */
    public static function createPasswordHash(string $password, int $algo = PASSWORD_BCRYPT, array $options = []): ?string
    {
        if (empty($options))
        {
            $options = [
                'cost' => 12,
            ];
        }

        if ($hash = password_hash($password, $algo, $options))
        {
            return $hash;
        }

        return null;
    }

    /**
     * @param string $password
     * @param string $passwordHash
     *
     * @return bool
     */
    public static function verifyPasswordHash(string $password, string $passwordHash): bool
    {
        return password_verify($password, $passwordHash);
    }

    /**
     * @param int $length
     * @param null|string $prefix
     * @param null|string $customCharacters
     *
     * @return string
     */
    public static function createRandomToken(int $length = 12, ?string $prefix = null, ?string $customCharacters = null): string
    {
        $randomString = '';
        $characters = self::TOKEN_ALL_CASE_LETTERS_NUMBERS;

        // set custom characters
        if ($customCharacters !== null && empty($customCharacters) === false)
        {
            $characters = $customCharacters;
        }

        // handle prefix
        if ($prefix !== null)
        {
            $prefixLength = strlen($prefix);
            $length -= $prefixLength;

            if ($length < 0)
            {
                $length = 0;
            }
        }

        // generate token
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $prefix . $randomString;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function createSessionId(int $length = 36): string
    {
        return self::createRandomToken($length);
    }
}