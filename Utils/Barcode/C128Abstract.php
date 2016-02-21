<?php

namespace phpOMS\Utils\Barcode;

use phpOMS\Datatypes\Exception\InvalidEnumValue;

abstract class C128Abstract
{
    protected static $CHECKSUM = 0;

    protected static $CODEARRAY = [];

    protected static $CODE_START = '';
    protected static $CODE_END   = '';

    protected $orientation = 0;
    protected $size        = 0;
    protected $dimension   = ['width' => 0, 'height' => 0];
    protected $content     = 0;
    protected $showText    = true;
    protected $margin      = ['top' => 0.0, 'right' => 0.0, 'bottom' => 0.0, 'left' => 0.0];
    protected $background  = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];
    protected $front       = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    public function __construct(string $content = '', int $size = 20, int $orientation = 0)
    {
        $this->content = $content;
        $this->setSize($size);
        $this->setOrientation($orientation);
    }

    public function setOrientation(int $orientation)
    {
        if (!OrientationType::isValidValue($orientation)) {
            throw new InvalidEnumValue($orientation);
        }

        $this->orientation = $orientation;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setSize(int $size)
    {
        if ($size < 0) {
            throw new \OutOfBoundsException($size);
        }

        $this->size = $size;
    }

    protected function generateCodeString()
    {
        $keys       = array_keys(self::$CODEARRAY);
        $values     = array_flip($keys);
        $codeString = '';
        $length     = strlen($this->content);
        $checksum   = self::$CHECKSUM;

        for ($pos = 1; $pos <= $length; $pos += 2) {
            $activeKey = substr($this->content, ($pos - 1), 1);
            $codeString .= self::$CODEARRAY[$activeKey];
            $checksum = ($checksum + ($values[$activeKey] * $pos));
        }

        $codeString .= self::$CODEARRAY[$keys[($checksum - (intval($checksum / 103) * 103))]];
        $codeString = self::$CODE_START . $codeString . self::$CODE_END;

        return $codeString;
    }

    public function get()
    {
        $codeString = self::$CODE_START . $this->generateCodeString() . self::$CODE_END;

        return $this->createImage($codeString, 20);
    }

    protected function createImage(string $codeString, int $codeLength = 20)
    {
        for ($i = 1; $i <= strlen($codeString); $i++) {
            $codeLength = $codeLength + (int) (substr($codeString, ($i - 1), 1));
        }

        if (strtolower($this->orientation) === OrientationType::HORIZONTAL) {
            $imgWidth  = $codeLength;
            $imgHeight = $this->size;
        } else {
            $imgWidth  = $this->size;
            $imgHeight = $codeLength;
        }

        $image    = imagecreate($imgWidth, $imgHeight);
        $black    = imagecolorallocate($image, 0, 0, 0);
        $white    = imagecolorallocate($image, 255, 255, 255);
        $location = 0;
        $length   = strlen($codeString);
        imagefill($image, 0, 0, $white);

        for ($position = 1; $position <= $length; $position++) {
            $cur_size = $location + (int) (substr($codeString, ($position - 1), 1));

            if (strtolower($this->orientation) === OrientationType::HORIZONTAL) {
                imagefilledrectangle($image, $location, 0, $cur_size, $imgHeight, ($position % 2 == 0 ? $white : $black));
            } else {
                imagefilledrectangle($image, 0, $location, $imgWidth, $cur_size, ($position % 2 == 0 ? $white : $black));
            }

            $location = $cur_size;
        }

        return $image;
    }
}
