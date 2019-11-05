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

/**
 * Cron class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 * @codeCoverageIgnore
 */
class Cron extends SchedulerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function create(TaskAbstract $task) : void
    {
        $this->run('-l > ' . __DIR__ . '/tmpcron.tmp');
        \file_put_contents(__DIR__ . '/tmpcron.tmp', $task->__toString() . "\n", \FILE_APPEND);
        $this->run(__DIR__ . '/tmpcron.tmp');
        \unlink(__DIR__ . '/tmpcron.tmp');
    }

    /**
     * {@inheritdoc}
     */
    public function update(TaskAbstract $task) : void
    {
        $this->run('-l > ' . __DIR__ . '/tmpcron.tmp');

        $new = '';
        $fp  = \fopen(__DIR__ . '/tmpcron.tmp', 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if ($line[0] !== '#' && \stripos($line, 'name="' . $task->getId()) !== false) {
                    $new .= $task->__toString() . "\n";
                } else {
                    $new .= $line;
                }

                $line = \fgets($fp);
            }

            \fclose($fp);
            \file_put_contents(__DIR__ . '/tmpcron.tmp', $new);
        }

        $this->run(__DIR__ . '/tmpcron.tmp');
        \unlink(__DIR__ . '/tmpcron.tmp');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TaskAbstract $task) : void
    {
        $this->deleteByName($task->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByName(string $name) : void
    {
        $this->run('-l > ' . __DIR__ . '/tmpcron.tmp');

        $new = '';
        $fp  = \fopen(__DIR__ . '/tmpcron.tmp', 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if ($line[0] !== '#' && \stripos($line, 'name="' . $name) !== false) {
                    $line = \fgets($fp);
                    continue;
                }

                $new .= $line;
                $line = \fgets($fp);
            }

            \fclose($fp);
            \file_put_contents(__DIR__ . '/tmpcron.tmp', $new);
        }

        $this->run(__DIR__ . '/tmpcron.tmp');
        \unlink(__DIR__ . '/tmpcron.tmp');
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $this->run('-l > ' . __DIR__ . '/tmpcron.tmp');

        $jobs = [];
        $fp   = \fopen(__DIR__ . '/tmpcron.tmp', 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if ($line[0] !== '#') {
                    $elements   = [];
                    $namePos    = \stripos($line, 'name="');
                    $nameEndPos = \stripos($line, '"', $namePos + 7);

                    if ($namePos !== false && $nameEndPos !== false) {
                        $elements[] = \substr($line, $namePos + 6, $nameEndPos - 1);
                    }

                    $elements = \array_merge($elements, \explode(' ', $line));
                    $jobs[]   = CronJob::createWith($elements);
                }

                $line = \fgets($fp);
            }

            \fclose($fp);
        }

        $this->run(__DIR__ . '/tmpcron.tmp');
        \unlink(__DIR__ . '/tmpcron.tmp');

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllByName(string $name, bool $exact = true) : array
    {
        $this->run('-l > ' . __DIR__ . '/tmpcron.tmp');

        $jobs = [];
        $fp   = \fopen(__DIR__ . '/tmpcron.tmp', 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if ($line[0] !== '#' && \stripos($line, '# name="' . $name) !== false) {
                    $elements   = [];
                    $elements[] = $name;
                    $elements  += \explode(' ', $line);
                    $jobs[]     = CronJob::createWith($elements);
                }

                $line = \fgets($fp);
            }

            \fclose($fp);
        }

        $this->run(__DIR__ . '/tmpcron.tmp');
        \unlink(__DIR__ . '/tmpcron.tmp');

        return $jobs;
    }
}
