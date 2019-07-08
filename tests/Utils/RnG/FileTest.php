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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\File;

/**
 * @internal
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testRnGExtension() : void
    {
        self::assertRegExp('/^[a-z0-9]{1,5}$/', File::generateExtension());
    }
}
