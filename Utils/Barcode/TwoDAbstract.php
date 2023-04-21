<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Barcode
 * @author    Nicola Asuni - Tecnick.com LTD - www.tecnick.com <info@tecnick.com>
 * @copyright Copyright (C) 2010 - 2014  Nicola Asuni - Tecnick.com LTD
 * @license   GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Barcode;

/**
 * 2DAbstract class.
 *
 * @package phpOMS\Utils\Barcode
 * @license GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class TwoDAbstract extends CodeAbstract
{
    /**
     * {@inheritdoc}
     */
    public function get() : mixed
    {
        $codeArray = $this->generateCodeArray();

        return $this->createImage($codeArray);
    }

    /**
     * Generate code array
     *
     * @return array
     *
     * @since 1.0.0
     */
    abstract public function generateCodeArray() : array;

    /**
     * Create barcode image
     *
     * @param array $codeArray Code array to render
     *
     * @return \GdImage
     *
     * @since 1.0.0
     */
    protected function createImage(array $codeArray) : mixed
    {
        $dimensions = $this->calculateDimensions($codeArray);
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

        $width  = \count($codeArray);
        $height = \count(\reset($codeArray));

        $multiplier = (int) (($dimensions['width'] - 2 * $this->margin) / $width);

        $locationX = $this->margin;

        // @todo: Allow manual dimensions
        for ($posX = 0; $posX < $width; ++$posX) {
            $locationY = $this->margin;

            for ($posY = 0; $posY < $height; ++$posY) {
                \imagefilledrectangle(
                    $image,
                    $locationX,
                    $locationY,
                    $locationX + $multiplier,
                    $locationY + $multiplier,
                    $codeArray[$posY][$posX] ? $black : $white
                );

                $locationY += $multiplier;
            }

            $locationX += $multiplier;
        }

        return $image;
    }

    /**
     * Calculate the code dimensions
     *
     * @param array $codeArray Code string to render
     *
     * @return array<string, int>
     *
     * @since 1.0.0
     */
    private function calculateDimensions(array $codeArray) : array
    {
        $matrixDimension = \max(\count($codeArray), \count(\reset($codeArray)));
        $imageDimension  = \max($this->dimension['width'], $this->dimension['width']);

        $multiplier = (int) ($imageDimension - 2 * $this->margin) / $matrixDimension;

        $dimensions['width']  = $matrixDimension * $multiplier + 2 * $this->margin;
        $dimensions['height'] = $matrixDimension * $multiplier + 2 * $this->margin;

        return $dimensions;
    }
}
