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

namespace Tests\PHPUnit\phpOMS\Utils\TaskSchedule;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Utils\TaskSchedule\TaskFactory;
use phpOMS\Utils\TaskSchedule\Schedule;
use phpOMS\Utils\TaskSchedule\CronJob;

class TaskFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testFactory()
    {
        self::assertTrue((TaskFactory::create('') instanceof CronJob) || (TaskFactory::create('') instanceof Schedule));
    }
}
