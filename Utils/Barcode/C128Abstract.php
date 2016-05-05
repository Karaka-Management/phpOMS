<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Barcode;

use phpOMS\Datatypes\Exception\InvalidEnumValue;

/**
 * Code 128 abstract class.
 *
 * @category   Log
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class C128Abstract
{
    /**
     * Checksum.
     *
     * @var int
     * @since 1.0.0
     */
    protected static $CHECKSUM = 0;

    /**
     * Char weighted array.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $CODEARRAY = [];

    /**
     * Code start.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $CODE_START = '';

    /**
     * Code end.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $CODE_END = '';

    /**
     * Orientation.
     *
     * @var int
     * @since 1.0.0
     */
    protected $orientation = 0;

    /**
     * Barcode height.
     *
     * @var int
     * @since 1.0.0
     */
    protected $size = 0;

    /**
     * Barcode dimension.
     *
     * @todo  : Implement!
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $dimension = ['width' => 0, 'height' => 0];

    /**
     * Content to encrypt.
     *
     * @var string|int
     * @since 1.0.0
     */
    protected $content = 0;

    /**
     * Show text below barcode.
     *
     * @var string
     * @since 1.0.0
     */
    protected $showText = true;

    /**
     * Margin for barcode (padding).
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $margin = ['top' => 0, 'right' => 4, 'bottom' => 0, 'left' => 4];

    /**
     * Background color.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $background = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    /**
     * Front color.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $front = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    /**
     * Constructor
     *
     * @param string $content     Content to encrypt
     * @param int    $size        Barcode height
     * @param int    $orientation Orientation of the barcode
     *
     * @todo   : add mirror parameter
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct(string $content = '', int $size = 20, int $orientation = OrientationType::HORIZONTAL)
    {
        $this->content = $content;
        $this->setSize($size);
        $this->setOrientation($orientation);
    }

    /**
     * Set barcode orientation
     *
     * @param int $orientation Barcode orientation
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function setOrientation(int $orientation)
    {
        if (!OrientationType::isValidValue($orientation)) {
            throw new InvalidEnumValue($orientation);
        }

        $this->orientation = $orientation;
    }

    /**
     * Set content to encrypt
     *
     * @param string $content Barcode content
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Set barcode height
     *
     * @param int $size Barcode height
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function setSize(int $size)
    {
        if ($size < 0) {
            throw new \OutOfBoundsException($size);
        }

        $this->size = $size;
    }

    /**
     * Generate weighted code string
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    protected function generateCodeString() : string
    {
        $keys       = array_keys(static::$CODEARRAY);
        $values     = array_flip($keys);
        $codeString = '';
        $length     = strlen($this->content);
        $checksum   = static::$CHECKSUM;

        for ($pos = 1; $pos <= $length; $pos++) {
            $activeKey = substr($this->content, ($pos - 1), 1);
            $codeString .= static::$CODEARRAY[$activeKey];
            $checksum += $values[$activeKey] * $pos;
        }

        $codeString .= static::$CODEARRAY[$keys[($checksum - (intval($checksum / 103) * 103))]];
        $codeString = static::$CODE_START . $codeString . static::$CODE_END;

        return $codeString;
    }

    /**
     * Get image reference
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function get()
    {
        $codeString = static::$CODE_START . $this->generateCodeString() . static::$CODE_END;

        return $this->createImage($codeString, 20);
    }

    /**
     * Create barcode image
     *
     * @param string $codeString Code string to render
     * @param int    $codeLength Barcode length (based on $codeString)
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    protected function createImage(string $codeString, int $codeLength = 20)
    {
        for ($i = 1; $i <= strlen($codeString); $i++) {
            $codeLength = $codeLength + (int) (substr($codeString, ($i - 1), 1));
        }

        if ($this->orientation === OrientationType::HORIZONTAL) {
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

            if ($this->orientation === OrientationType::HORIZONTAL) {
                imagefilledrectangle($image, $location, 0, $cur_size, $imgHeight, ($position % 2 == 0 ? $white : $black));
            } else {
                imagefilledrectangle($image, 0, $location, $imgWidth, $cur_size, ($position % 2 == 0 ? $white : $black));
            }

            $location = $cur_size;
        }

        return $image;
    }
}
