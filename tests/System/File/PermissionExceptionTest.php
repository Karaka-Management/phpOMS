<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\System\File;

use phpOMS\System\File\PermissionException;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\File\PermissionException::class)]
final class PermissionExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testConstructor() : void
    {
        $e = new PermissionException('test.file');
        self::assertStringContainsString('test.file', $e->getMessage());
        self::assertEquals(0, $e->getCode());
        $this->isInstanceOf('\RuntimeException');
    }
}
