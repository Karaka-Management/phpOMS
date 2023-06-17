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
 * Code 25 class.
 *
 * @package phpOMS\Utils\Barcode
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
class C25 extends BarAbstract
{
    /**
     * Char array.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $CODEARRAY = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

    /**
     * Char weighted array.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $CODEARRAY2 = [
        '3-1-1-1-3', '1-3-1-1-3', '3-3-1-1-1', '1-1-3-1-3', '3-1-3-1-1',
        '1-3-3-1-1', '1-1-1-3-3', '3-1-1-3-1', '1-3-1-3-1', '1-1-3-3-1',
    ];

    /**
     * Code start.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $CODE_START = '1111';

    /**
     * Code end.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $CODE_END = '311';

    /**
     * Set content to encrypt
     *
     * @param string $content Barcode content
     *
     * @return void
     *
     * @throws \InvalidArgumentException this exception is thrown if the content string is not supported
     *
     * @since 1.0.0
     */
    public function setContent(string $content) : void
    {
        if (!\ctype_digit($content)) {
            throw new \InvalidArgumentException($content);
        }

        parent::setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateCodeString() : string
    {
        $codeString  = '';
        $length      = \strlen($this->content);
        $arrayLength = \count(self::$CODEARRAY);
        $temp        = [];

        for ($posX = 1; $posX <= $length; ++$posX) {
            for ($posY = 0; $posY < $arrayLength; ++$posY) {
                if (\substr($this->content, ($posX - 1), 1) === self::$CODEARRAY[$posY]) {
                    $temp[$posX] = self::$CODEARRAY2[$posY];
                }
            }
        }

        for ($posX = 1; $posX <= $length; $posX += 2) {
            if (isset($temp[$posX], $temp[($posX + 1)])) {
                $temp1 = \explode('-', $temp[$posX]);
                $temp2 = \explode('-', $temp[($posX + 1)]);

                $count = \count($temp1);
                for ($posY = 0; $posY < $count; ++$posY) {
                    $codeString .= $temp1[$posY] . $temp2[$posY];
                }
            }
        }

        return $codeString;
    }
}
