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

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\SchedulerFactory;
use phpOMS\Utils\TaskSchedule\TaskScheduler;
use phpOMS\Utils\TaskSchedule\Cron;

class SchedulerFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testFactory()
    {
        self::assertTrue((SchedulerFactory::create('') instanceof Cron) || (SchedulerFactory::create('') instanceof TaskScheduler));

        // todo: make full test here by defining schtask or crontab path
        // todo: create task
        // todo: get task
        // todo: remove task
    }
}
