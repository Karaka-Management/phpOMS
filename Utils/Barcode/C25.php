<?php

namespace phpOMS\Utils\Barcode;

class C25 extends C128Abstract
{
    protected static $CODEARRAY  = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
    protected static $CODEARRAY2 = [
        '3-1-1-1-3', '1-3-1-1-3', '3-3-1-1-1', '1-1-3-1-3', '3-1-3-1-1',
        '1-3-3-1-1', '1-1-1-3-3', '3-1-1-3-1', '1-3-1-3-1', '1-1-3-3-1',
    ];

    protected static $CODE_START = '1111';

    protected static $CODE_END = '311';

    public function __construct(string $content = '', int $size = 20, int $orientation = 0)
    {
        if(!ctype_digit($content)) {
            throw new \InvalidArgumentException($content);
        }

        parent::__construct($content, $size, $orientation);
    }

    public function setContent(string $content)
    {
        if(!ctype_digit($content)) {
            throw new \InvalidArgumentException($content);
        }

        parent::setContent($content);
    }

    protected function generateCodeString()
    {
        $codeString  = '';
        $length      = strlen($this->content);
        $arrayLength = count(self::$CODEARRAY);

        for ($posX = 1; $posX <= $length; $posX++) {
            for ($posY = 0; $posY < $arrayLength; $posY++) {
                if (substr($this->content, ($posX - 1), 1) == self::$CODEARRAY[$posY]) {
                    $temp[$posX] = self::$CODEARRAY2[$posY];
                }
            }
        }

        for ($posX = 1; $posX <= $length; $posX += 2) {
            if (isset($temp[$posX]) && isset($temp[($posX + 1)])) {
                $temp1 = explode('-', $temp[$posX]);
                $temp2 = explode('-', $temp[($posX + 1)]);

                for ($posY = 0; $posY < count($temp1); $posY++) {
                    $codeString .= $temp1[$posY] . $temp2[$posY];
                }
            }
        }

        return $codeString;
    }
}
