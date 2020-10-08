<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\System\File;

use phpOMS\System\File\PermissionException;

/**
 * @internal
 */
class PermissionExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\System\File\PermissionException
     * @group framework
     */
    public function testConstructor() : void
    {
        $e = new PermissionException('test.file');
        self::assertStringContainsString('test.file', $e->getMessage());
        self::assertEquals(0, $e->getCode());
        $this->isInstanceOf('\RuntimeException');
    }
}
