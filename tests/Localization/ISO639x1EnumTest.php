<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO639x1Enum;

/**
 * @internal
 */
class ISO639x1EnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO639x1Enum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 2) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        self::assertEquals(\count($enum), \count(\array_unique($enum)));
    }
}
