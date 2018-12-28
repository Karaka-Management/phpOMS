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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests;

class ExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function testExtensionMbstring() : void
    {
        self::assertTrue(extension_loaded('mbstring'));
    }

    public function testExtensionCurl() : void
    {
        self::assertTrue(extension_loaded('curl'));
    }

    public function testExtensionImap() : void
    {
        self::assertTrue(extension_loaded('imap'));
    }

    public function testExtensionPdo() : void
    {
        self::assertTrue(extension_loaded('pdo'));
    }

    public function testExtensionGD() : void
    {
        self::assertTrue(extension_loaded('gd') || extension_loaded('gd2'));
    }
}
