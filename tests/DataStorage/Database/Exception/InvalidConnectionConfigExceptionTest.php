<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database\Exception;


use phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException;

class InvalidConnectionConfigExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testException()
    {
        self::assertInstanceOf(\InvalidArgumentException::class, new InvalidConnectionConfigException(''));
    }
}
