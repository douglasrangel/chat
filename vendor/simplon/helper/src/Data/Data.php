<?php

namespace Simplon\Helper\Data;

use Simplon\Helper\Interfaces\DataInterface;

abstract class Data implements DataInterface
{
    /**
     * @var string
     */
    private $internalChecksum;

    /**
     * @param array|null $data
     */
    public function __construct(?array $data = null)
    {
        if ($data)
        {
            $this->fromArray($data, false);
        }

        $this->internalChecksum = $this->calcMd5($this->toRawArray());
    }

    /**
     * @return bool
     */
    public function isChanged(): bool
    {
        return $this->internalChecksum !== $this->calcMd5($this->toRawArray());
    }

    /**
     * @param array $data
     * @param bool $buildChecksum Build checksum of data. Use FALSE in case you're rebuilding existing object.
     *
     * @return static
     */
    public function fromArray(array $data, bool $buildChecksum = true)
    {
        if ($data)
        {
            foreach ($data as $fieldName => $val)
            {
                // format field name
                if (strpos($fieldName, '_') !== false)
                {
                    $fieldName = self::camelCaseString($fieldName);
                }

                $setMethodName = 'set' . ucfirst($fieldName);

                // set on setter
                if (method_exists($this, $setMethodName))
                {
                    $this->$setMethodName($val);
                    continue;
                }

                // set on field
                if (property_exists($this, $fieldName))
                {
                    $this->$fieldName = $val;
                    continue;
                }
            }

            if ($buildChecksum)
            {
                $this->internalChecksum = $this->calcMd5($this->toRawArray());
            }
        }

        return $this;
    }

    /**
     * @param bool $snakeCase
     *
     * @return array
     */
    public function toArray(bool $snakeCase = true): array
    {
        $result = [];

        $visibleFields = get_class_vars(get_called_class());

        // render column names
        foreach ($visibleFields as $fieldName => $value)
        {
            $propertyName = $fieldName;
            $getMethodName = 'get' . ucfirst($fieldName);

            // format field name
            if ($snakeCase === true && strpos($fieldName, '_') === false)
            {
                $fieldName = self::snakeCaseString($fieldName);
            }

            // get from getter
            if (method_exists($this, $getMethodName))
            {
                $result[$fieldName] = $this->$getMethodName();
                continue;
            }

            // get from field
            if (property_exists($this, $propertyName))
            {
                if ($propertyName !== 'internalChecksum')
                {
                    $result[$fieldName] = $this->$propertyName;
                    continue;
                }
            }
        }

        return $result;
    }

    /**
     * @param bool $snakeCase
     *
     * @return string
     */
    public function toJson(bool $snakeCase = true): string
    {
        return json_encode(
            $this->toArray($snakeCase)
        );
    }

    /**
     * @param string $json
     * @param bool $buildChecksum Build checksum of data. Use FALSE in case you're rebuilding existing object.
     *
     * @return static
     */
    public function fromJson(string $json, bool $buildChecksum = true)
    {
        return $this->fromArray(json_decode($json, true), $buildChecksum);
    }

    /**
     * @param $string
     *
     * @return string
     */
    protected static function snakeCaseString($string)
    {
        return strtolower(preg_replace('/([A-Z1-9])/', '_\\1', $string));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected static function camelCaseString($string)
    {
        $string = strtolower($string);
        $string = ucwords(str_replace('_', ' ', $string));

        return lcfirst(str_replace(' ', '', $string));
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function calcMd5(array $data): string
    {
        ksort($data);

        return md5(json_encode($data));
    }

    /**
     * @param bool $snakeCase
     *
     * @return array
     */
    private function toRawArray(bool $snakeCase = true): array
    {
        $result = [];

        $visibleFields = get_class_vars(get_called_class());

        // render column names
        foreach ($visibleFields as $fieldName => $value)
        {
            $propertyName = $fieldName;

            // format field name
            if ($snakeCase === true && strpos($fieldName, '_') === false)
            {
                $fieldName = self::snakeCaseString($fieldName);
            }

            // get from field
            if (property_exists($this, $propertyName))
            {
                if ($propertyName !== 'internalChecksum')
                {
                    $result[$fieldName] = $this->$propertyName;
                    continue;
                }
            }
        }

        return $result;
    }
}