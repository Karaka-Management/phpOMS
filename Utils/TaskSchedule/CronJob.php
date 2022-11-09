<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

/**
 * CronJob class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class CronJob extends TaskAbstract
{
    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->id === '' || $this->command === ''
            ? ''
            : $this->interval . ' ' . $this->command . ' # name="' . $this->id . '" ' . $this->comment;
    }

    /**
     * {@inheritdoc}
     */
    public static function createWith(array $jobData) : TaskAbstract
    {
        $interval = \array_splice($jobData, 1, 5);
        $job      = new self($jobData[0], $jobData[1], \implode(' ', $interval));

        return $job;
    }
}
