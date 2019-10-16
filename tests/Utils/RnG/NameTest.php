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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Name;

/**
 * @internal
 */
class NameTest extends \PHPUnit\Framework\TestCase
{
    public function testRandom() : void
    {
        self::assertNotEquals(Name::generateName(['female']), Name::generateName(['male']));
    }
}
