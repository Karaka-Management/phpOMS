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

namespace phpOMS\tests\Stdlib\Base\Exception;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;

/**
 * @internal
 */
final class InvalidEnumValueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Stdlib\Base\Exception\InvalidEnumValue
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidEnumValue(''));
    }
}
