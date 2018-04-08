<?php

namespace Simplon\Helper;

class DataUri
{
    /**
     * @param string $data
     *
     * @return string
     * @throws \Exception
     */
    public static function decode(string $data): string
    {
        if ($content = base64_decode(preg_replace('/data:.*?;base64,/', '', $data)))
        {
            return $content;
        }

        throw new \Exception('Could not decode data');
    }

    /**
     * @param string $content
     *
     * @return string
     * @throws \Exception
     */
    public static function encode(string $content): string
    {
        if ($mimeType = DetectMimeType::detect($content))
        {
            return 'data: ' . $mimeType . ';base64,' . base64_encode($content);
        }

        throw new \Exception('Could not detect mime type');
    }
}