<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\TaskSchedule
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\Validation\Base\DateTime;

/**
 * CronJob class.
 *
 * @package    phpOMS\Utils\TaskSchedule
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class CronJob extends TaskAbstract
{
    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->interval . ' ' . $this->command . ' # name="' . $this->id . '" ' . $this->comment;
    }
    
    /**
     * {@inheritdoc}
     */
    public static function createWith(array $jobData) : TaskAbstract
    {
        $interval = \array_splice($jobData, 1, 4);
        $job      = new self($jobData[0], $jobData[1], \implode(' ', $interval));

        return $job;
    }
}
