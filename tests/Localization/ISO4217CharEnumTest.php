<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO4217CharEnum;

/**
 * @internal
 */
final class ISO4217CharEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO4217CharEnum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 3) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        self::assertEquals(\count($enum), \count(\array_unique($enum)));
    }
}
