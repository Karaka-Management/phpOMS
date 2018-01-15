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

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\Vector;

class VectorTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        self::assertInstanceOf('\phpOMS\Math\Matrix\Vector', new Vector());

        $vec = new Vector(5);
        self::assertEquals(5, count($vec->toArray()));
    }
}
