<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Barcode
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Barcode;

/**
 * Code 128 abstract class.
 *
 * @package phpOMS\Utils\Barcode
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
abstract class BarAbstract extends CodeAbstract
{
    /**
     * Checksum.
     *
     * @var int
     * @since 1.0.0
     */
    protected static int $CHECKSUM = 0;

    /**
     * Char weighted array.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $CODEARRAY = [];

    /**
     * Code start.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $CODE_START = '';

    /**
     * Code end.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $CODE_END = '';

    /**
     * Show text below barcode.
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $showText = true;

    /**
     * {@inheritdoc}
     */
    public function get() : mixed
    {
        $codeString = static::$CODE_START . $this->generateCodeString() . static::$CODE_END;

        return $this->createImage($codeString);
    }

    /**
     * Validate the barcode string
     *
     * @param string $barcode Barcode string
     *
     * @return bool Returns true if the string is valid for the specific code implementetion otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function isValidString(string $barcode) : bool
    {
        $length = \strlen($barcode);

        for ($i = 0; $i < $length; ++$i) {
            if (!isset(static::$CODEARRAY[$barcode[$i]]) && !\in_array($barcode[$i], static::$CODEARRAY)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate weighted code string
     *
     * @return string Returns the code string generated from the human readable content
     *
     * @since 1.0.0
     */
    protected function generateCodeString() : string
    {
        if ($this->codestring === '') {
            $keys     = \array_keys(static::$CODEARRAY);
            $values   = \array_flip($keys);
            $length   = \strlen($this->content);
            $checksum = static::$CHECKSUM;

            for ($pos = 1; $pos <= $length; ++$pos) {
                $activeKey = \substr($this->content, ($pos - 1), 1);
                $this->codestring .= static::$CODEARRAY[$activeKey];
                $checksum += $values[$activeKey] * $pos;
            }

            $this->codestring .= static::$CODEARRAY[$keys[($checksum - ((int) ($checksum / 103) * 103))]];
        }

        return $this->codestring;
    }

    /**
     * Create barcode image
     *
     * @param string $codeString Code string to render
     *
     * @return \GdImage
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    protected function createImage(string $codeString) : mixed
    {
        $dimensions = $this->calculateDimensions($codeString);
        $image      = \imagecreate($dimensions['width'], $dimensions['height']);

        if ($image === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        $black = \imagecolorallocate($image, 0, 0, 0);
        $white = \imagecolorallocate($image, 255, 255, 255);

        if ($white === false || $black === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        \imagefill($image, 0, 0, $white);

        $location = 0;
        $length   = \strlen($codeString);

        for ($position = 1; $position <= $length; ++$position) {
            $cur_size = $location + (int) (\substr($codeString, ($position - 1), 1));

            if ($this->orientation === OrientationType::HORIZONTAL) {
                \imagefilledrectangle(
                    $image,
                    $location + $this->margin,
                    0 + $this->margin,
                    $cur_size + $this->margin,
                    $dimensions['height'] - $this->margin - 1,
                    ($position % 2 === 0 ? $white : $black)
                );
            } else {
                \imagefilledrectangle(
                    $image,
                    0 + $this->margin,
                    $location + $this->margin,
                    $dimensions['width'] - $this->margin - 1,
                    $cur_size + $this->margin,
                    ($position % 2 === 0 ? $white : $black)
                );
            }

            $location = $cur_size;
        }

        return $image;
    }

    /**
     * Calculate the code length for image dimensions
     *
     * @param string $codeString Code string to render
     *
     * @return int Length of the code
     *
     * @since 1.0.0
     */
    private function calculateCodeLength(string $codeString) : int
    {
        $codeLength = 0;
        $length     = \strlen($codeString);

        for ($i = 1; $i <= $length; ++$i) {
            $codeLength += (int) (\substr($codeString, ($i - 1), 1));
        }

        return $codeLength;
    }

    /**
     * Calculate the code dimensions
     *
     * @param string $codeString Code string to render
     *
     * @return array<string, int>
     *
     * @since 1.0.0
     */
    private function calculateDimensions(string $codeString) : array
    {
        $codeLength = $this->calculateCodeLength($codeString);
        $dimensions = ['width' => 0, 'height' => 0];

        if ($this->orientation === OrientationType::HORIZONTAL) {
            $dimensions['width']  = $codeLength + $this->margin * 2 + 1;
            $dimensions['height'] = $this->dimension['height'];
        } else {
            $dimensions['width']  = $this->dimension['width'];
            $dimensions['height'] = $codeLength + $this->margin * 2 + 1;
        }

        return $dimensions;
    }
}
