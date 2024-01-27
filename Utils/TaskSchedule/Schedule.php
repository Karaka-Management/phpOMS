<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\Validation\Base\DateTime;

/**
 * Schedule class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
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
        /**
         * @todo Karaka/phpOMS#231
         *  Use the interval for generating a schedule
         */
        $job = new self($jobData[1], $jobData[8], $jobData[7]);

        $job->status = (int) $jobData[3];

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
