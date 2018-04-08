<?php

namespace Simplon\Helper;

class CastAway
{
    /**
     * @param mixed $val
     *
     * @return int|null
     */
    public static function toInt($val)
    {
        return self::hasValue($val) ? (int)trim($val) : null;
    }

    /**
     * @param mixed $val
     *
     * @return string|null
     */
    public static function toString($val)
    {
        return self::hasValue($val) ? (string)trim($val) : null;
    }

    /**
     * @param mixed $val
     *
     * @return bool|null
     */
    public static function toBool($val)
    {
        return self::hasValue($val) ? $val === true : null;
    }

    /**
     * @param mixed $val
     *
     * @return float|null
     */
    public static function toFloat($val)
    {
        return self::hasValue($val) ? (float)trim($val) : null;
    }

    /**
     * @param mixed $val
     *
     * @return array|null
     */
    public static function toArray($val)
    {
        return self::hasValue($val) ? (array)$val : null;
    }

    /**
     * @param mixed $val
     *
     * @return object|null
     */
    public static function toObject($val)
    {
        return self::hasValue($val) ? (object)$val : null;
    }

    /**
     * @param mixed $val
     * @param \DateTimeZone $dateTimeZone
     *
     * @return \DateTime|null
     */
    public static function toDateTime($val, \DateTimeZone $dateTimeZone = null)
    {
        return self::hasValue($val) ? new \DateTime(trim($val), $dateTimeZone) : null;
    }

    /**
     * @param string $json
     *
     * @return array|null
     */
    public static function jsonToArray(string $json)
    {
        return json_decode($json, true);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public static function arrayToJson(array $data): string
    {
        return json_encode($data);
    }

    /**
     * @param int $int
     *
     * @return bool
     */
    public static function intToBool(int $int): bool
    {
        return $int === 1 ? true : false;
    }

    /**
     * @param bool $bool
     *
     * @return int
     */
    public static function boolToInt(bool $bool): int
    {
        return $bool === true ? 1 : 0;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function trimPath(string $path): string
    {
        return rtrim($path, '/');
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public static function trimUrl(string $url): string
    {
        return rtrim($url, '/');
    }

    /**
     * @param string $string
     * @param array $placeholders
     * @param string $enclosed
     *
     * @return string
     */
    public static function renderPlaceholders(string $string, array $placeholders, string $enclosed = '{}'): string
    {
        $left = '\\' . substr($enclosed, 0, 1);
        $right = '\\' . substr($enclosed, 1);

        foreach ($placeholders as $key => $val)
        {
            $string = preg_replace('/' . $left . $key . $right . '/i', $val, $string);
        }

        return $string;
    }

    /**
     * @param array $data
     *
     * @return int[]
     */
    public static function toArrayInt(array $data): array
    {
        foreach ($data as $k => $v)
        {
            $data[$k] = self::toInt($v);
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return string[]
     */
    public static function toArrayString(array $data): array
    {
        foreach ($data as $k => $v)
        {
            $data[$k] = self::toString($v);
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return float[]
     */
    public static function toArrayFloat(array $data): array
    {
        foreach ($data as $k => $v)
        {
            $data[$k] = self::toFloat($v);
        }

        return $data;
    }

    /**
     * @param int $int
     *
     * @return string
     * @throws \Exception
     */
    public static function intToColumnWord(int $int): string
    {
        $word = null;

        switch ($int)
        {
            case 1:
                $word = 'one';
                break;

            case 2:
                $word = 'two';
                break;

            case 3:
                $word = 'three';
                break;

            case 4:
                $word = 'four';
                break;

            case 5:
                $word = 'five';
                break;

            case 6:
                $word = 'six';
                break;

            case 7:
                $word = 'seven';
                break;

            case 8:
                $word = 'eight';
                break;

            case 9:
                $word = 'nine';
                break;

            case 10:
                $word = 'ten';
                break;

            case 11:
                $word = 'eleven';
                break;

            case 12:
                $word = 'twelve';
                break;

            case 13:
                $word = 'thirteen';
                break;

            case 14:
                $word = 'fourteen';
                break;

            case 15:
                $word = 'fifthteen';
                break;

            case 16:
                $word = 'sixthteen';
                break;

            default:
                throw new \Exception('Integer is greater than possible column count');
        }

        return $word;
    }

    /**
     * @param mixed $val
     *
     * @return bool
     */
    private static function hasValue($val)
    {
        return $val !== null && $val !== '';
    }
}