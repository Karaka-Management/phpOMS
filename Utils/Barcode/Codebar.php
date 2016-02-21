<?php

namespace phpOMS\Utils\Barcode;

class Codebar extends C128Abstract
{
    protected static $CODEARRAY  = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '$', ':', '/', '.', '+', 'A', 'B', 'C', 'D'];
    protected static $CODEARRAY2 = [
        '1111221', '1112112', '2211111', '1121121', '2111121', '1211112', '1211211', '1221111', '2112111', '1111122',
        '1112211', '1122111', '2111212', '2121112', '2121211', '1121212', '1122121', '1212112', '1112122', '1112221',
    ];

    protected static $CODE_START = '11221211';

    protected static $CODE_END = '1122121';

    public function __construct(string $content = '', int $size = 20, int $orientation = 0)
    {
        parent::__construct(strtoupper($content), $size, $orientation);
    }

    public function setContent(string $content)
    {
        parent::setContent(strtoupper($content));
    }

    protected function generateCodeString()
    {
        $codeString = '';
        $length     = strlen($this->content);

        for ($posX = 1; $posX <= $length; $posX++) {
            for ($posY = 0; $posY < count(self::$CODEARRAY); $posY++) {
                if (substr($this->content, ($posX - 1), 1) == self::$CODEARRAY[$posY])
                    $codeString .= self::$CODEARRAY2[$posY] . '1';
            }
        }

        return $codeString;
    }
}
