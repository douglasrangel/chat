<?php

namespace Simplon\Helper;

class DetectMimeType
{
    const FORMATS = [
        'image/jpeg'                   => ['name' => 'JPEG/JIFF Image', 'ext' => ['jpg', 'jpeg', 'jpe']],
        'image/png'                    => ['name' => 'Portable (Public) Network Graphic', 'ext' => ['png']],
        'image/gif'                    => ['name' => 'Graphic Interchange Format', 'ext' => ['gif']],
        'image/tga'                    => ['name' => 'Truevision Targa Graphic', 'ext' => ['tga']],
        'image/tif'                    => ['name' => 'Tagged Image Format File', 'ext' => ['tif']],
        'image/bmp'                    => ['name' => 'Windows OS/2 Bitmap Graphic', 'ext' => ['bmp']],
        'image/photoshop'              => ['name' => 'Photoshop Format', 'ext' => ['psd']],
        'application/msword'           => ['name' => 'Word Document', 'ext' => ['doc']],
        'application/msexcel'          => ['name' => 'Excel Worksheet', 'ext' => ['xls']],
        'video/avi'                    => ['name' => 'Audio Video Interleave File', 'ext' => ['avi']],
        'audio/wav'                    => ['name' => 'Waveform Audio', 'ext' => ['wav']],
        'audio/mid'                    => ['name' => 'MIDI-sequention Sound', 'ext' => ['mid', 'midi']],
        'audio/mpeg'                   => ['name' => 'MPEG Audio Stream, Layer III', 'ext' => ['mp3']],
        'video/mpeg'                   => ['name' => 'MPEG 1 System Stream', 'ext' => ['mpg', 'mpeg']],
        'video/quicktime'              => ['name' => 'QuickTime Video Clip', 'ext' => ['mov']],
        'application/x-zip-compressed' => ['name' => 'Compressed Archive File', 'ext' => ['zip']],
        'application/x-rar-compressed' => ['name' => 'WinRAR Compressed Archive', 'ext' => ['rar', 'r01']],
        'application/x-ace-compressed' => ['name' => 'WinAce Compressed File', 'ext' => ['ace']],
        'application/x-7z-compressed'  => ['name' => '7-Zip Compressed File', 'ext' => ['7z']],
        'font/ttf'                     => ['name' => 'TrueType Font', 'ext' => ['ttf']],
        'font/otf'                     => ['name' => 'Open Type Font Format', 'ext' => ['otf']],
    ];

    const BYTE_REFS = [
        '89504e470d0a1a0a0000000d49484452' => 'image/png',
        '38425053000100000000000000'       => 'image/photoshop',
        '4d54686400000006000100'           => 'audio/mid',
        'd0cf11e0a1b11ae100'               => 'application/msexcel',
        'd0cf11e0a1b11ae1'                 => 'application/msword',
        '526172211a0700'                   => 'application/x-rar-compressed',
        '2a2a4143452a2a'                   => 'application/x-ace-compressed',
        '377abcaf271c'                     => 'application/x-7z-compressed',
        '0001000000'                       => 'application/font-sfnt',
        '4f54544f00'                       => 'application/font-sfnt',
        '504b0304'                         => 'application/x-zip-compressed',
        '52494646'                         => 'video/avi',
        '47494638'                         => 'image/gif',
        '49492a00'                         => 'image/tif',
        '4d4d002a'                         => 'image/tif',
        '4944330'                          => 'audio/mpeg',
        '000001'                           => 'video/mpeg',
        'ffd8ff'                           => 'image/jpeg',
        '424d'                             => 'image/bmp',
        '6d'                               => 'video/quicktime',
        '00'                               => 'image/tga',
        'ff'                               => 'audio/mp3',
    ];

    /**
     * @var int
     */
    private static $maxLengthToRead;

    /**
     * @param string $content
     *
     * @return null|string
     */
    public static function detect(string $content): ?string
    {
        $header = bin2hex(
            substr($content, 0, self::getMaxLengthToRead())
        );

        foreach (self::BYTE_REFS as $ident => $mime)
        {
            if (substr($header, 0, strlen($ident)) === (string)$ident)
            {
                return $mime;
            }
        }

        return null;
    }

    /**
     * @param string $mimeType
     *
     * @return array|null
     */
    public static function getInfo(string $mimeType): ?array
    {
        if (isset(self::FORMATS[$mimeType]))
        {
            return self::FORMATS[$mimeType];
        }

        return null;
    }

    /**
     * @return int
     */
    private static function getMaxLengthToRead(): int
    {
        if (!self::$maxLengthToRead)
        {
            $maxLength = 0;

            foreach (self::BYTE_REFS as $ident => $mime)
            {
                $len = strlen($ident);

                if ($len > $maxLength)
                {
                    $maxLength = $len;
                }
            }

            self::$maxLengthToRead = $maxLength;
        }

        return self::$maxLengthToRead;
    }
}