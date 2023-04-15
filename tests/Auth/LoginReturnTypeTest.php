<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Auth;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Auth\LoginReturnType;

/**
 * @testdox phpOMS\tests\Auth\LoginReturnType: Login return type
 * @internal
 */
final class LoginReturnTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The login return type enum has the correct number of type codes
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(11, LoginReturnType::getConstants());
    }

    /**
     * @testdox The login return type enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(LoginReturnType::getConstants(), \array_unique(LoginReturnType::getConstants()));
    }

    /**
     * @testdox The login return type enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, LoginReturnType::OK);
        self::assertEquals(-1, LoginReturnType::FAILURE);
        self::assertEquals(-2, LoginReturnType::WRONG_PASSWORD);
        self::assertEquals(-3, LoginReturnType::WRONG_USERNAME);
        self::assertEquals(-4, LoginReturnType::WRONG_PERMISSION);
        self::assertEquals(-5, LoginReturnType::NOT_ACTIVATED);
        self::assertEquals(-6, LoginReturnType::WRONG_INPUT_EXCEEDED);
        self::assertEquals(-7, LoginReturnType::TIMEOUTED);
        self::assertEquals(-8, LoginReturnType::BANNED);
        self::assertEquals(-9, LoginReturnType::INACTIVE);
        self::assertEquals(-10, LoginReturnType::EMPTY_PASSWORD);
    }
}
