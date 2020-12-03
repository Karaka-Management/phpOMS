<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

class ManyToManyDirectModel
{
    public $id = 0;

    public $string = 'ManyToManyDirect';

    public int $to = 0;
}
