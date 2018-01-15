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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Name;

class NameTest extends \PHPUnit\Framework\TestCase
{
    public function testRandom()
    {
        self::assertNotEquals(Name::generateName(['female']), Name::generateName(['male']));
    }
}
