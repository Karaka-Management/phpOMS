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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO639Enum;

/**
 * @internal
 */
class ISO639EnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        $enum = ISO639Enum::getConstants();
        self::assertEquals(\count($enum), \count(\array_unique($enum)));
    }
}
