<?php
/**
 * Jingga
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

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullAccount;

/**
 * @testdox phpOMS\tests\Account\NullAccount: Null account
 * @internal
 */
final class NullAccountTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The null account is an instance of the account class
     * @covers phpOMS\Account\NullAccount
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Account', new NullAccount());
    }

    /**
     * @testdox The null account can get initialized with an id
     * @covers phpOMS\Account\NullAccount
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullAccount(2);
        self::assertEquals(2, $null->getId());
    }

    public function testJsonSerialization() : void
    {
        $null = new NullAccount(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
