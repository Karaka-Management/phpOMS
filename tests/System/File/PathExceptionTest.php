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

namespace phpOMS\tests\System\File;

use phpOMS\System\File\PathException;

/**
 * @internal
 */
final class PathExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\System\File\PathException
     * @group framework
     */
    public function testConstructor() : void
    {
        $e = new PathException('test.file');
        self::assertStringContainsString('test.file', $e->getMessage());
        self::assertEquals(0, $e->getCode());
        $this->isInstanceOf('\UnexpectedValueException');
    }
}
