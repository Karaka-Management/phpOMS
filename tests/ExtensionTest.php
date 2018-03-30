<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests;

class ExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function testExtension()
    {
        self::assertTrue(extension_loaded('mbstring'));
        self::assertTrue(extension_loaded('curl'));
        self::assertTrue(extension_loaded('imap'));
        self::assertTrue(extension_loaded('gd') || extension_loaded('gd2'));
    }
}
