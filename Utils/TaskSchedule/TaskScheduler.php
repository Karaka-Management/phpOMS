<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\TaskSchedule;

/**
 * Task scheduler class.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class TaskScheduler extends SchedulerAbstract
{

    const BIN = '';

    /**
     * Env variables.
     *
     * @var array
     * @since 1.0.0
     */
    private $envOptions = [];

    public function save()
    {

    }

    /**
     * Run command
     *
     * @param string $cmd Command to run
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function run(string $cmd) : array
    {
        $cmd = 'cd ' . escapeshellarg(dirname(self::BIN)) . ' && ' . basename(self::BIN) . ' -C ' . escapeshellarg(__DIR__) . ' ' . $cmd;

        $pipes = [];
        $desc  = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        if (count($_ENV) === 0) {
            $env = null;
            foreach ($this->envOptions as $key => $value) {
                putenv(sprintf("%s=%s", $key, $value));
            }
        } else {
            $env = array_merge($_ENV, $this->envOptions);
        }

        $resource = proc_open($cmd, $desc, $pipes, __DIR__, $env);
        $stdout   = stream_get_contents($pipes[1]);
        $stderr   = stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        $status = trim(proc_close($resource));

        if ($status == -1) {
            throw new \Exception($stderr);
        }

        return trim($stdout);
    }

    public function getAll() : array
    {
        return str_getcsv($this->run('/query /v /fo CSV'));
    }

    public function get(string $id)
    {

    }

    public function getByName(string $name) : Schedule
    {

    }

    public function getAllByName(string $name) : array
    {
        return str_getcsv($this->run('/query /v /fo CSV /tn ' . escapeshellarg($name)));
    }

    public function create(Schedule $task)
    {

    }
}
