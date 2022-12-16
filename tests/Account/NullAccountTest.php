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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullAccount;

/**
 * @internal
 */
final class NullAccountTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Account\NullAccount
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Account', new NullAccount());
    }

    /**
     * @covers phpOMS\Account\NullAccount
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullAccount(2);
        self::assertEquals(2, $null->getId());
    }
}
