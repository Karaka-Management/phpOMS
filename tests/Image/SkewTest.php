<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Image;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Image\Skew;

/**
 * @internal
 */
final class SkewTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testSkew() : void
    {
        /* Disabled because of very slow performance (244 seconds) */
        self::markTestSkipped();

        Skew::autoRotate(
            __DIR__ . '/binary_tilted.png',
            __DIR__ . '/test_binary_untilted.png',
            10,
            [150, 75],
            [1700, 900]
        );
    }
}
