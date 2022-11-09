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

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullGroup;

/**
 * @internal
 */
final class NullGroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Account\NullGroup
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Group', new NullGroup());
    }
}
