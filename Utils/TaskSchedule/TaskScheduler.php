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

use phpOMS\Validation\Base\DateTime;

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

    protected static $bin = 'c:/WINDOWS/system32/schtasks.exe';

    /**
     * Env variables.
     *
     * @var array
     * @since 1.0.0
     */
    private $envOptions = [];

    /**
     * Test git.
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function test() : bool
    {
        $pipes    = [];
        $resource = proc_open(escapeshellarg(self::$bin), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        return trim(proc_close($resource)) !== 127;
    }

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
    private function run(string $cmd) : string
    {
        $cmd = 'cd ' . escapeshellarg(dirname(self::$bin)) . ' && ' . basename(self::$bin) . ' ' . $cmd;

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

    private function normalize(string $raw) : string 
    {
        return str_replace("\r\n", "\n", $raw);
    }

    private function parseJobList(array $jobData) {
            $job = TaskFactory::create($jobData[1], '');

            $job->setRun($jobData[8]);
            $job->setStatus($jobData[3]);

            if(DateTime::isValid($jobData[2])) { 
                $job->setNextRunTime(new \DateTime($jobData[2]));
            }

            if(DateTime::isValid($jobData[5])) { 
                $job->setLastRunTime(new \DateTime($jobData[5]));
            }
            
            $job->setAuthor($jobData[7]);
            $job->setComment($jobData[10]);

            if(DateTime::isValid($jobData[20])) { 
                $job->setStart(new \DateTime($jobData[20]));
            }

            if(DateTime::isValid($jobData[21])) { 
                $job->setEnd(new \DateTime($jobData[21]));
            }

            $job->addResult($jobData[6]);

            return $job;
    }

    public function getAll() : array
    {
        $lines = explode("\n", $this->normalize($this->run('/query /v /fo CSV')));
        unset($lines[0]);

        $jobs = [];
        foreach($lines as $line) {
            $jobs[] = $this->parseJobList(str_getcsv($line));
        }
        
        return $jobs;
    }

    public function get(string $id)
    {

    }

    public function getByName(string $name) : Schedule
    {

    }

    public function getAllByName(string $name, bool $exact = true) : array
    {
        if($exact) {
            $lines = $this->run('/query /v /fo CSV /tn ' . escapeshellarg($name));
            unset($lines[0]);

            $jobs = [];
            foreach($lines as $line) {
                $jobs[] = $this->parseJobList(str_getcsv($line));
            }
        } else {
            $lines = explode("\n", $this->normalize($this->run('/query /v /fo CSV')));
            $jobs = [];
            
            unset($lines[0]);

            foreach($lines as $key => $line) {
                $line = str_getcsv($line);

                if(strpos($line[1], $name) !== false) {
                    $jobs[] = $this->parseJobList($line);
                }
            }
        }

        return $jobs;
    }

    public function create(Schedule $task)
    {

    }
}
