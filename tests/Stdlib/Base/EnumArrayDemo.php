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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\EnumArray;

final class EnumArrayDemo extends EnumArray
{
    protected static $constants = [
        'ENUM1' => 1,
        'ENUM2' => 'abc',
    ];
}
