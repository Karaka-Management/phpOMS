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
    public function testExtensionMbstring()
    {
        self::assertTrue(extension_loaded('mbstring'));
    }

    public function testExtensionCurl()
    {
        self::assertTrue(extension_loaded('curl'));
    }

    public function testExtensionImap()
    {
        self::assertTrue(extension_loaded('imap'));
    }

    public function testExtensionPdo()
    {
        self::assertTrue(extension_loaded('pdo'));
    }

    public function testExtensionGD()
    {
        self::assertTrue(extension_loaded('gd') || extension_loaded('gd2'));
    }
}
