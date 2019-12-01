<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\Validation\Base\DateTime;

/**
 * Schedule class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Schedule extends TaskAbstract
{
    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->interval === '' ? '' : '/tn ' . $this->id . ' ' . $this->interval .  ' ' . $this->command;
    }

    /**
     * {@inheritdoc}
     */
    public static function createWith(array $jobData) : TaskAbstract
    {
        // todo: fix interval this is just a dummy|!!!
        $job = new self($jobData[1], $jobData[8], 'asdf');

        $job->setStatus($jobData[3]);

        if (DateTime::isValid($jobData[2])) {
            $job->setNextRunTime(new \DateTime($jobData[2]));
        }

        if (DateTime::isValid($jobData[5])) {
            $job->setLastRuntime(new \DateTime($jobData[5]));
        }

        $job->setComment($jobData[10] ?? '');

        return $job;
    }
}
