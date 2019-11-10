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

use phpOMS\Localization\ISO4217SubUnitEnum;

/**
 * @internal
 */
class ISO4217SubUnitEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO4217SubUnitEnum::getConstants();

        foreach ($enum as $code) {
            if ($code < 0 || $code > 1000) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
    }
}
