<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\IbanEnum;

/**
 * @internal
 */
final class IbanEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $enums = IbanEnum::getConstants();
        $ok    = true;

        foreach ($enums as $enum) {
            $temp = \substr($enum, 2);

            if (\preg_match('/[^kbsxcinm0at\ ]/', $temp) === 1) {
                $ok = false;

                break;
            }
        }

        self::assertTrue($ok);
    }
}
