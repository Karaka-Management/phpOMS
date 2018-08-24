<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\InverseType;

class InverseTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(1, \count(InverseType::getConstants()));
        self::assertEquals(InverseType::getConstants(), array_unique(InverseType::getConstants()));

        self::assertEquals(0, InverseType::GAUSS_JORDAN);
    }
}
