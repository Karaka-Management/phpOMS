<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
 declare(strict_types=1);

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullAccount;

/**
 * @internal
 */
class NullAccountTest extends \PHPUnit\Framework\TestCase
{
    public function testNull() : void
    {
        self::assertInstanceOf('\phpOMS\Account\Account', new NullAccount());
    }
}
