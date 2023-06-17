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

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;

/**
 * Code class.
 *
 * @package phpOMS\Utils\Barcode
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
abstract class CodeAbstract
{
    /**
     * Code.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $codestring = '';

    /**
     * Code.
     *
     * Bool array since I don't know the internal size of a char.
     * Bool should be at least same internal size or smaller (has no length).
     * In a different programming language char is most likeley better.
     *
     * You could even consider to change from 2D to 1D by using strings and than iterate the string.
     *
     * @var array<int, array<int, bool>>
     * @since 1.0.0
     */
    protected array $codearray = [[]];

    /**
     * Orientation.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $orientation = 0;

    /**
     * Barcode dimension.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected array $dimension = ['width' => 0, 'height' => 0];

    /**
     * Barcode dimension.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $margin = 10;

    /**
     * Content to encrypt.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $content = '';

    /**
     * Background color.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected array $background = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    /**
     * Front color.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected array $front = ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0];

    /**
     * Constructor
     *
     * @param string $content     Content to encrypt
     * @param int    $width       Barcode width
     * @param int    $height      Barcode height
     * @param int    $orientation Orientation of the barcode
     *
     * @since 1.0.0
     */
    public function __construct(string $content = '', int $width = 100, int $height = 20, int $orientation = OrientationType::HORIZONTAL)
    {
        $this->setContent($content);
        $this->setDimension($width, $height);
        $this->setOrientation($orientation);
    }

    /**
     * Set barcode dimensions
     *
     * @param int $width  Barcode width
     * @param int $height Barcode height
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDimension(int $width, int $height) : void
    {
        if ($width < 0) {
            throw new \OutOfBoundsException((string) $width);
        }

        if ($height < 0) {
            throw new \OutOfBoundsException((string) $height);
        }

        $this->dimension['width']  = $width;
        $this->dimension['height'] = $height;
    }

    /**
     * Set barcode margins
     *
     * @param int $margin Barcode margin
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMargin(int $margin) : void
    {
        $this->margin = $margin;
    }

    /**
     * Set barcode orientation
     *
     * @param int $orientation Barcode orientation
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setOrientation(int $orientation) : void
    {
        if (!OrientationType::isValidValue($orientation)) {
            throw new InvalidEnumValue($orientation);
        }

        $this->orientation = $orientation;
    }

    /**
     * Get content
     *
     * @return string Returns the string representation of the code
     *
     * @since 1.0.0
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Set content to encrypt
     *
     * @param string $content Barcode content
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setContent(string $content) : void
    {
        $this->content = $content;

        $this->codestring = '';
        $this->codearray  = [];
    }

    /**
     * Save to file
     *
     * @param string $file File path/name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function saveToPngFile(string $file) : void
    {
        $res = $this->get();

        \imagepng($res, $file);
        \imagedestroy($res);
    }

    /**
     * Save to file
     *
     * @param string $file File path/name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function saveToJpgFile(string $file) : void
    {
        $res = $this->get();

        \imagejpeg($res, $file);
        \imagedestroy($res);
    }

    /**
     * Get image reference
     *
     * @return \GdImage
     *
     * @since 1.0.0
     */
    abstract public function get() : mixed;
}
