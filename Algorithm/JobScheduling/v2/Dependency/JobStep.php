<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Scheduling\Dependency
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling\Dependency;

/**
 * Job step.
 *
 * @package phpOMS\Scheduling\Dependency
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class JobStep
{
    public int $id = 0;

    public int $order = 0;

    public int $l11n = 0;

    public int $machineType = 0;

    public bool $machineParallelization = false;

    public array $machines = [];

    public int $workerType = 0;

    public array $workerQualifications = []; // qualifications needed

    public bool $workerParallelization = false;

    public array $workers = [];

    public int $material = 0;

    public int $materialQuantity = 0;

    public int $duration = 0; // in seconds

    public int $maxHoldTime = -1; // minutes it can be halted if necessary (-1 infinite, 0 not at all)

    public int $maxHoldAfterCompletion = -1; // minutes the next processing step can be postponed (-1 infinite, 0 not at all)

    private int $realDuration = 0;

    // depending on job completions
    private array $jobDependencies = [];

    public bool $shouldBeParallel = false;

    /**
     * Duration
     *  + machine type/machine specific times (e.g. setup time etc.)
     *  + machine-job specific times (e.g. setup time for this job which could be different from the general machine setup time)
     */
    public function calculateDuration() : void
    {
        $this->realDuration = $this->duration;
    }
}
