<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\MimeType;

/**
 * @internal
 */
class MimeTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnum() : void
    {
        $enums = MimeType::getConstants();

        foreach ($enums as $key => $value) {
            if (\stripos($value, '/') === false) {
                self::assertFalse(true);
            }
        }

        self::assertTrue(true);
    }
}
