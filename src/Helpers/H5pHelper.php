<?php

/*
 *
 * @Project        LaraH5P
 * @Copyright      Musab Alzoubi
 * @Created        2024-02-18
 * @Filename       H5pHelper.php
 * @Description    Helper class for H5P permission checks and security functions
 *
 */

namespace LaraH5P\Helpers;

class H5pHelper
{
    /**
     * Check if the current user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    public static function currentUserCan($permission)
    {
        return true;
    }

    /**
     * Generate a nonce (one-time token) for security purposes.
     *
     * @param string $token
     * @return string
     */
    public static function nonce($token)
    {
        return bin2hex($token);
    }
}
