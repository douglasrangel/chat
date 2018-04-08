<?php

namespace Simplon\Helper\Data;

class InstanceData
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $cacheName;
    /**
     * @var array
     */
    private $params = [];
    /**
     * @var callable
     */
    private $paramsBuilder;
    /**
     * @var callable
     */
    private $afterCallback;

    /**
     * @param string $className
     *
     * @return InstanceData
     */
    public static function create(string $className): InstanceData
    {
        return new InstanceData($className);
    }

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
        $this->cacheName = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return InstanceData
     */
    public function setClassName(string $className): InstanceData
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getCacheName(): string
    {
        return $this->cacheName;
    }

    /**
     * @param string $cacheName
     *
     * @return InstanceData
     */
    public function setCacheName(string $cacheName): InstanceData
    {
        $this->cacheName = $cacheName;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @param mixed $param
     *
     * @return InstanceData
     */
    public function addParam($param): InstanceData
    {
        $this->params[] = $param;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return InstanceData
     */
    public function setParams(array $params): InstanceData
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return callable
     */
    public function getParamsBuilder(): callable
    {
        return $this->paramsBuilder;
    }

    /**
     * @return bool
     */
    public function hasParamsBuilder(): bool
    {
        return is_callable($this->paramsBuilder);
    }

    /**
     * @param callable $paramsBuilder
     *
     * @return InstanceData
     */
    public function setParamsBuilder(callable $paramsBuilder): InstanceData
    {
        $this->paramsBuilder = $paramsBuilder;

        return $this;
    }

    /**
     * @return callable
     */
    public function getAfterCallback(): callable
    {
        return $this->afterCallback;
    }

    /**
     * @return bool
     */
    public function hasAfterCallback(): bool
    {
        return is_callable($this->afterCallback);
    }

    /**
     * @param callable $afterCallback
     *
     * @return InstanceData
     */
    public function setAfterCallback(callable $afterCallback): InstanceData
    {
        $this->afterCallback = $afterCallback;

        return $this;
    }
}