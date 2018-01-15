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

namespace Tests\PHPUnit\phpOMS\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\InvalidUriException;

class InvalidUriExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testException()
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidUriException(''));
    }
}
