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

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\SchedulerAbstract;

/**
 * @internal
 */
class SchedulerAbstractTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertTrue(SchedulerAbstract::getBin() === '' || \file_exists(SchedulerAbstract::getBin()));
    }
}
