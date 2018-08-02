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
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

/**
 * Cron class.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 * @codeCoverageIgnore
 */
class Cron extends SchedulerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function create(TaskAbstract $task) : void
    {
        $this->run('-l > tmpcron');
        \file_put_contents('tmpcron', "\n" . $task->__toString(), FILE_APPEND);
        $this->run('tmpcron');
    }

    /**
     * {@inheritdoc}
     */
    public function update(TaskAbstract $task) : void
    {
        $this->run('-l > tmpcron');

        $new = '';
        $fp  = \fopen('tmpcron', 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if ($line[0] !== '#' && \stripos($line, '/tn = ' . $task->getId()) !== false) {
                    $new .= $task->__toString();
                } else {
                    $new .= $line . "\n";
                }

                $line = \fgets($fp);
            }

            \fwrite($fp, $new);
            \fclose($fp);
        }

        $this->run('tmpcron');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TaskAbstract $task) : void
    {
        $this->run('-l > tmpcron');

        $new = '';
        $fp  = \fopen('tmpcron', 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if ($line[0] !== '#' && \stripos($line, '/tn = ' . $task->getId()) !== false) {
                    $line = \fgets($fp);
                    continue;
                }

                $new .= $line . "\n";
                $line = \fgets($fp);
            }

            \fwrite($fp, $new);
            \fclose($fp);
        }

        $this->run('tmpcron');
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $lines = \explode("\n", $this->normalize($this->run('-l')));
        unset($lines[0]);

        $jobs = [];
        foreach ($lines as $line) {
            if ($line !== '' && \strrpos($line, '#', -\strlen($line)) === false) {
                $jobs[] = CronJob::createWith(\str_getcsv($line, ' '));
            }
        }

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllByName(string $name, bool $exact = true) : array
    {
        $lines = \explode("\n", $this->normalize($this->run('-l')));
        unset($lines[0]);

        if ($exact) {
            $jobs = [];
            foreach ($lines as $line) {
                $csv = \str_getcsv($line, ' ');

                if ($line !== '' && \strrpos($line, '#', -\strlen($line)) === false && $csv[5] === $name) {
                    $jobs[] = CronJob::createWith($csv);
                }
            }
        } else {
            $jobs = [];
            foreach ($lines as $line) {
                $csv = \str_getcsv($line, ' ');

                if ($line !== '' && \strrpos($line, '#', -\strlen($line)) === false && \stripos($csv[5], $name) !== false) {
                    $jobs[] = CronJob::createWith($csv);
                }
            }
        }

        return $jobs;
    }
}
