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

namespace phpOMS\tests\Auth;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Auth\LoginReturnType;

/**
 * @internal
 */
class LoginReturnTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(11, LoginReturnType::getConstants());
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
