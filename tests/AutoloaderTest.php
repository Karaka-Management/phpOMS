<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests;

use phpOMS\Autoloader;

/**
 * @internal
 */
class AutoloaderTest extends \PHPUnit\Framework\TestCase
{
    public function testAutoloader() : void
    {
        self::assertTrue(Autoloader::exists('\phpOMS\Autoloader'));
        self::assertFalse(Autoloader::exists('\Does\Not\Exist'));
    }
}
