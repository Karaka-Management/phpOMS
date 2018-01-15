<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace Tests\PHPUnit\phpOMS\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\NullAccount;

class NullAccountTest extends \PHPUnit\Framework\TestCase
{
    public function testNull()
    {
        self::assertInstanceOf('\phpOMS\Account\Account', new NullAccount());
    }
}
