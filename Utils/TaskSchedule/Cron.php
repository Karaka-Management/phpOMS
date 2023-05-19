<?php
/**
 * Karaka
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

use phpOMS\System\SystemUtils;

/**
 * Cron class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
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
        $path = \tempnam(\sys_get_temp_dir(), 'cron_');
        if ($path === false) {
            throw new \Exception();
        }

        if (!empty($this->getAllByName($task->getId()))) {
            \unlink($path);

            return;
        }

        $this->run('-l > ' . $path);
        \file_put_contents($path, $task->__toString() . "\n", \FILE_APPEND);
        $this->run($path);
        \unlink($path);
    }

    /**
     * {@inheritdoc}
     */
    public function update(TaskAbstract $task) : void
    {
        $path = \tempnam(\sys_get_temp_dir(), 'cron_');
        if ($path === false) {
            throw new \Exception();
        }

        $this->run('-l > ' . $path);

        $new = '';
        $fp  = \fopen($path, 'r+');

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
            \file_put_contents($path, $new);
        }

        $this->run($path);
        \unlink($path);
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
        $path = \tempnam(\sys_get_temp_dir(), 'cron_');
        if ($path === false) {
            throw new \Exception();
        }

        $this->run('-l > ' . $path);

        $new = '';
        $fp  = \fopen($path, 'r+');

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
            \file_put_contents($path, $new);
        }

        $this->run($path);
        \unlink($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $path = \tempnam(\sys_get_temp_dir(), 'cron_');
        if ($path === false) {
            throw new \Exception();
        }

        $this->run('-l > ' . $path);

        $jobs = [];
        $fp   = \fopen($path, 'r+');

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

        $this->run($path);
        \unlink($path);

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllByName(string $name, bool $exact = true) : array
    {
        $path = \tempnam(\sys_get_temp_dir(), 'cron_');
        if ($path === false) {
            throw new \Exception();
        }

        $this->run('-l > ' . $path);

        $jobs = [];
        $fp   = \fopen($path, 'r+');

        if ($fp) {
            $line = \fgets($fp);
            while ($line !== false) {
                if (($comment = \stripos($line, '# name="' . $name)) !== false) {
                    $interval = \array_slice(\explode(' ', $line), 0, 5);

                    $elements   = [];
                    $elements[] = \trim(\substr($line, $comment + 8, \stripos($line, '"', $comment + 9) - $comment - 8));
                    $elements   = \array_merge($elements, $interval);
                    $elements[] = \trim(\substr($line, $len = (\strlen(\implode(' ', $interval)) + 1), $comment - $len - 1));

                    $jobs[] = $job = CronJob::createWith($elements);
                    $job->setStatus($line[0] === '#' ? TaskStatus::INACTIVE : TaskStatus::ACTIVE);
                }

                $line = \fgets($fp);
            }

            \fclose($fp);
        }

        $this->run($path);
        \unlink($path);

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function reload() : void
    {
        SystemUtils::runProc('service', 'cron reload');
    }
}
