<?php

namespace Simplon\Helper;

use Simplon\Helper\Data\InstanceData;

class Instances
{
    /**
     * @var array
     */
    private static $cache = [];

    /**
     * @param InstanceData $data
     *
     * @return mixed
     */
    public static function cache(InstanceData $data)
    {
        if (empty(self::$cache[$data->getCacheName()]))
        {
            if ($data->hasParamsBuilder())
            {
                $builder = $data->getParamsBuilder();
                $data->setParams($builder());
            }

            $className = $data->getClassName();
            self::$cache[$data->getCacheName()] = new $className(...$data->getParams());

            if ($data->hasAfterCallback())
            {
                $afterCallback = $data->getAfterCallback();
                self::$cache[$data->getCacheName()] = $afterCallback(self::$cache[$data->getCacheName()]);
            }
        }

        return self::$cache[$data->getCacheName()];
    }

}