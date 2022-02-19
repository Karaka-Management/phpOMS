<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\EnumArray;

final class EnumArrayDemo extends EnumArray
{
    protected static array $constants = [
        'ENUM1' => 1,
        'ENUM2' => 'abc',
    ];
}
