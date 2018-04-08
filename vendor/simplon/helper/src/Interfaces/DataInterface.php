<?php

namespace Simplon\Helper\Interfaces;

interface DataInterface
{
    /**
     * @return bool
     */
    public function isChanged(): bool;

    /**
     * @param array $data
     *
     * @return static
     */
    public function fromArray(array $data);

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param bool $snakeCase
     *
     * @return string
     */
    public function toJson(bool $snakeCase = true): string;

    /**
     * @param string $json
     *
     * @return static
     */
    public function fromJson(string $json);
}