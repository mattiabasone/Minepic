<?php

declare(strict_types=1);

namespace App\Helpers\Storage;

/**
 * Class Storage.
 */
class Storage
{
    /**
     * Storage folder type.
     *
     * @var string
     */
    protected static $folder;

    /**
     * Skin Path.
     *
     * @param string $uuid
     *
     * @return string
     */
    public static function getPath(string $uuid = ''): string
    {
        if ('' == $uuid) {
            $uuid = env('DEFAULT_USERNAME');
        }

        return \sprintf(storage_path(static::$folder.'/%s.png'), $uuid);
    }

    /**
     * Checks if file exists.
     *
     * @param string $uuid
     *
     * @return bool
     */
    public static function exists(string $uuid = ''): bool
    {
        return \file_exists(static::getPath($uuid));
    }

    /**
     * Save the skin to file.
     *
     * @param string $uuid
     * @param mixed  $rawData
     *
     * @return bool
     */
    public static function save(string $uuid, $rawData): bool
    {
        $fp = \fopen(static::getPath($uuid), 'wb');
        if ($fp) {
            \fwrite($fp, $rawData);
            \fclose($fp);

            return true;
        }

        return false;
    }

    /**
     * Use Steve file for given uuid.
     *
     * @param mixed
     *
     * @return bool
     */
    public static function copyAsSteve(string $string = ''): bool
    {
        if ('' != $string) {
            return \copy(
                static::getPath(env('DEFAULT_USERNAME')),
                static::getPath($string)
            );
        }

        return false;
    }
}
